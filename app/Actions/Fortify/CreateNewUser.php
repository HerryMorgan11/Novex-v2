<?php

namespace App\Actions\Fortify;

use App\Models\Tenant;
use App\Models\TenantMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Stancl\Tenancy\Events\TenantCreated;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
        ])->validate();

        [$user, $tenant] = DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            // Avoid firing TenantCreated inside an open DB transaction.
            // With sync queues, tenancy jobs can run immediately and execute DDL, causing implicit commits.
            $tenant = Tenant::withoutEvents(function () use ($input, $user) {
                return Tenant::create([
                    'name' => $input['name'].' Workspace',
                    'slug' => Str::slug($input['name']).'-'.Str::lower(Str::random(6)),
                    'status' => 'provisioning',
                    'created_by_user_id' => $user->id,
                ]);
            });

            TenantMembership::create([
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'is_owner' => true,
                'status' => 'active',
                'joined_at' => now(),
            ]);

            $user->forceFill([
                'current_tenant_id' => $tenant->id,
            ])->save();

            return [$user->fresh(), $tenant];
        });

        event(new TenantCreated($tenant));

        return $user;
    }
}

<?php

namespace App\Actions\Tenancy;

use App\Models\Tenant;
use App\Models\TenantMembership;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Crea un nuevo usuario dentro de un tenant.
 *
 * El usuario se crea con status 'pending' en TenantMembership, lo que
 * obliga a cambiar la contraseña en el primer inicio de sesión.
 * La contraseña provisional se retorna en texto plano solo en la respuesta,
 * nunca se almacena en logs ni en claro en la base de datos.
 */
class CreateTenantUserAction
{
    /**
     * @param  array{name: string, email: string, role: string}  $data
     * @return array{user: User, plain_password: string}
     */
    public function execute(Tenant $tenant, array $data): array
    {
        $plainPassword = $this->generatePassword();

        $user = User::on('central')->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($plainPassword),
            'is_active' => true,
            'current_tenant_id' => $tenant->id,
        ]);

        TenantMembership::create([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'is_owner' => false,
            'role' => $data['role'],
            'status' => 'pending',
            'joined_at' => null,
        ]);

        return [
            'user' => $user,
            'plain_password' => $plainPassword,
        ];
    }

    /**
     * Genera una contraseña aleatoria segura de 12 caracteres.
     * Garantiza al menos una mayúscula, una minúscula, un número y un símbolo.
     */
    private function generatePassword(): string
    {
        $upper = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 2);
        $lower = substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 3);
        $numbers = substr(str_shuffle('23456789'), 0, 3);
        $symbols = substr(str_shuffle('!@#$%&*'), 0, 2);
        $rest = Str::random(2);

        return str_shuffle($upper.$lower.$numbers.$symbols.$rest);
    }
}

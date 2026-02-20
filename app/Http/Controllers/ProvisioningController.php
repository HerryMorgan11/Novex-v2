<?php

namespace App\Http\Controllers;

use App\Tenancy\Jobs\FinalizeProvisioning;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;

class ProvisioningController extends Controller
{
    public function page()
    {
        return view('auth.provisioning');
    }

    public function status(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['status' => 'guest'], 401);
        }

        $tenant = $user->currentTenant ?? ($user->membership?->tenant ?? null);

        if (! $tenant) {
            return response()->json(['status' => 'no-tenant']);
        }

        // If still provisioning and no jobs queued, re-dispatch the provisioning pipeline
        if ($tenant->status === 'provisioning' && DB::table('jobs')->count() === 0) {
            try {
                app()->call([new CreateDatabase($tenant), 'handle']);
            } catch (\Exception) {
                // DB may already exist — that's fine
            }
            app()->call([new MigrateDatabase($tenant), 'handle']);
            app()->call([new FinalizeProvisioning($tenant), 'handle']);
            $tenant->refresh();
        }

        return response()->json([
            'status' => $tenant->status,
            'db_name' => $tenant->db_name ?? $tenant->tenancy_db_name ?? null,
        ]);
    }
}

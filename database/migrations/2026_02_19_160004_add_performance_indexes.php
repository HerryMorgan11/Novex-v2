<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar índices compuestos y mejorar performance
        Schema::table('tenant_memberships', function (Blueprint $table) {
            // Índice compuesto para búsquedas frecuentes (tenant_id + status)
            $table->index(['tenant_id', 'status']);

            // Índice compuesto para encontrar propietarios
            $table->index(['tenant_id', 'is_owner']);

            // Índice para búsquedas por usuario y tenant
            $table->index(['user_id', 'tenant_id']);
        });

        // Agregar índices a users para búsquedas
        Schema::table('users', function (Blueprint $table) {
            $table->index(['email']);
            $table->index(['current_tenant_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('tenant_memberships', function (Blueprint $table) {
            if ($this->indexExists('tenant_memberships', 'tenant_memberships_tenant_id_status_index')) {
                $table->dropIndex('tenant_memberships_tenant_id_status_index');
            }

            if ($this->indexExists('tenant_memberships', 'tenant_memberships_tenant_id_is_owner_index')) {
                $table->dropIndex('tenant_memberships_tenant_id_is_owner_index');
            }

            if ($this->indexExists('tenant_memberships', 'tenant_memberships_user_id_tenant_id_index')) {
                $table->dropIndex('tenant_memberships_user_id_tenant_id_index');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if ($this->indexExists('users', 'users_email_index')) {
                $table->dropIndex('users_email_index');
            }

            if ($this->indexExists('users', 'users_current_tenant_id_is_active_index')) {
                $table->dropIndex('users_current_tenant_id_is_active_index');
            }
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return DB::table('information_schema.statistics')
            ->whereRaw('table_schema = database()')
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }
};

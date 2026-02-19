<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

        // Agregar índices a domains para búsquedas frecuentes
        Schema::table('domains', function (Blueprint $table) {
            $table->index(['tenant_id']);
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
            $table->dropIndex(['tenant_id', 'status']);
            $table->dropIndex(['tenant_id', 'is_owner']);
            $table->dropIndex(['user_id', 'tenant_id']);
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['current_tenant_id', 'is_active']);
        });
    }
};

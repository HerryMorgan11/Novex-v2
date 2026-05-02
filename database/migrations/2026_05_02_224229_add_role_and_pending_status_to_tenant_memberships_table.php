<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar enum status para incluir 'pending'
        DB::statement("ALTER TABLE tenant_memberships MODIFY COLUMN status ENUM('active','invited','pending','disabled') NOT NULL DEFAULT 'active'");

        Schema::table('tenant_memberships', function (Blueprint $table) {
            // Añadir columna de rol
            $table->enum('role', ['admin', 'manager', 'empleado'])
                ->default('empleado')
                ->after('is_owner');
        });

        // El propietario del tenant es admin por defecto
        DB::statement("UPDATE tenant_memberships SET role = 'admin' WHERE is_owner = 1");
    }

    public function down(): void
    {
        Schema::table('tenant_memberships', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        DB::statement("ALTER TABLE tenant_memberships MODIFY COLUMN status ENUM('active','invited','disabled') NOT NULL DEFAULT 'active'");
    }
};

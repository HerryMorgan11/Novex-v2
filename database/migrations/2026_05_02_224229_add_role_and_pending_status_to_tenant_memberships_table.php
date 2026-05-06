<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL: usar ALTER TABLE MODIFY
            DB::statement("ALTER TABLE tenant_memberships MODIFY COLUMN status ENUM('active','invited','pending','disabled') NOT NULL DEFAULT 'active'");
        } elseif ($driver === 'sqlite') {
            // SQLite: recrear la tabla con la nueva definición Y la columna role
            DB::statement('PRAGMA foreign_keys=OFF');

            // Crear tabla temporal con todas las columnas incluyendo role
            DB::statement(<<<'SQL'
                CREATE TABLE tenant_memberships_new (
                    id INTEGER PRIMARY KEY,
                    user_id CHAR(26) NOT NULL UNIQUE,
                    tenant_id VARCHAR(255) NOT NULL,
                    is_owner BOOLEAN NOT NULL DEFAULT 0,
                    role VARCHAR(255) NOT NULL DEFAULT 'empleado' CHECK (role IN ('admin', 'manager', 'empleado')),
                    status VARCHAR(255) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'invited', 'pending', 'disabled')),
                    joined_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    deleted_at TIMESTAMP NULL,
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
                    FOREIGN KEY (user_id) REFERENCES users(id)
                )
            SQL);

            // Copiar datos preservando todos los campos, rol por defecto
            DB::statement(<<<'SQL'
                INSERT INTO tenant_memberships_new (id, user_id, tenant_id, is_owner, role, status, joined_at, created_at, updated_at, deleted_at)
                SELECT id, user_id, tenant_id, is_owner, 'empleado', status, joined_at, created_at, updated_at, deleted_at FROM tenant_memberships
            SQL);

            // Eliminar tabla antigua
            DB::statement('DROP TABLE tenant_memberships');

            // Renombrar nueva tabla
            DB::statement('ALTER TABLE tenant_memberships_new RENAME TO tenant_memberships');

            DB::statement('PRAGMA foreign_keys=ON');
        }

        // Añadir columna de rol solo en MySQL (SQLite ya la añadió al recrear)
        if ($driver === 'mysql' && ! Schema::hasColumn('tenant_memberships', 'role')) {
            Schema::table('tenant_memberships', function (Blueprint $table) {
                $table->enum('role', ['admin', 'manager', 'empleado'])
                    ->default('empleado')
                    ->after('is_owner');
            });
        }

        // El propietario del tenant es admin por defecto
        DB::statement("UPDATE tenant_memberships SET role = 'admin' WHERE is_owner = 1");
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        Schema::table('tenant_memberships', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE tenant_memberships MODIFY COLUMN status ENUM('active','invited','disabled') NOT NULL DEFAULT 'active'");
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            DB::statement(<<<'SQL'
                CREATE TABLE tenant_memberships_new (
                    id INTEGER PRIMARY KEY,
                    user_id CHAR(26) NOT NULL UNIQUE,
                    tenant_id VARCHAR(255) NOT NULL,
                    is_owner BOOLEAN NOT NULL DEFAULT 0,
                    status VARCHAR(255) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'invited', 'disabled')),
                    joined_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    deleted_at TIMESTAMP NULL,
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
                    FOREIGN KEY (user_id) REFERENCES users(id)
                )
            SQL);

            DB::statement('INSERT INTO tenant_memberships_new (id, user_id, tenant_id, is_owner, status, joined_at, created_at, updated_at, deleted_at) SELECT id, user_id, tenant_id, is_owner, status, joined_at, created_at, updated_at, deleted_at FROM tenant_memberships');

            DB::statement('DROP TABLE tenant_memberships');

            DB::statement('ALTER TABLE tenant_memberships_new RENAME TO tenant_memberships');

            DB::statement('PRAGMA foreign_keys=ON');
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Legacy migration kept for compatibility. The referenced columns
        // and foreign key are already created in create_tenants_table.
    }

    public function down(): void
    {
        // No-op, see up().
    }
};

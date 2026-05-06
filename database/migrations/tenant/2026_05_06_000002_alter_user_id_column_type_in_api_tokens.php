<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Direct ALTER TABLE to fix user_id column type
        // This handles the case where the column already exists but is the wrong type
        DB::statement('ALTER TABLE `api_tokens_inventario` MODIFY COLUMN `user_id` CHAR(26) NULL');
    }

    public function down(): void
    {
        // Revert to the previous type if needed
        DB::statement('ALTER TABLE `api_tokens_inventario` MODIFY COLUMN `user_id` BIGINT UNSIGNED NULL');
    }
};

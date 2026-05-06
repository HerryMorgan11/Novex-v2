<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If column exists and is wrong type, recreate it
        if (Schema::hasColumn('api_tokens_inventario', 'user_id')) {
            // Check if it's the wrong type (bigInteger instead of char)
            $columns = DB::select("SHOW COLUMNS FROM `api_tokens_inventario` WHERE Field = 'user_id'");
            if ($columns && strpos($columns[0]->Type, 'bigint') !== false) {
                // Drop and recreate with correct type
                Schema::table('api_tokens_inventario', function (Blueprint $table) {
                    $table->dropColumn('user_id');
                });

                Schema::table('api_tokens_inventario', function (Blueprint $table) {
                    $table->char('user_id', 26)->nullable()->after('id');
                });
            }
        }
    }

    public function down(): void
    {
        // No need to revert - the previous migration handles cleanup
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('api_tokens_inventario') && ! Schema::hasColumn('api_tokens_inventario', 'user_id')) {
            // Add user_id column as CHAR(26) to match User ULID type
            Schema::table('api_tokens_inventario', function (Blueprint $table) {
                $table->char('user_id', 26)->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('api_tokens_inventario', 'user_id')) {
            Schema::table('api_tokens_inventario', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};

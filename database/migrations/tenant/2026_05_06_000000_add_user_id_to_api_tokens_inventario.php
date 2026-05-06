<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_tokens_inventario', function (Blueprint $table) {
            // Add user_id column if it doesn't exist
            // User model uses HasUlids, so user_id must be string, not bigInteger
            if (! Schema::hasColumn('api_tokens_inventario', 'user_id')) {
                $table->char('user_id', 26)->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('api_tokens_inventario', function (Blueprint $table) {
            if (Schema::hasColumn('api_tokens_inventario', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};

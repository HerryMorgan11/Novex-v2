<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Agregar campos de empresa si no existen
            if (! Schema::hasColumn('tenants', 'industry')) {
                $table->string('industry')->nullable()->after('name');
            }
            if (! Schema::hasColumn('tenants', 'country')) {
                $table->string('country')->nullable()->after('industry');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['industry', 'country']);
        });
    }
};

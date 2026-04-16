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
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_unidad_medida')->nullable()->change();
            $table->unsignedBigInteger('id_categoria')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_unidad_medida')->nullable(false)->change();
            $table->unsignedBigInteger('id_categoria')->nullable(false)->change();
        });
    }
};

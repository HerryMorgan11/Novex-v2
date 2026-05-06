<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Lotes
        Schema::create('lotes', function (Blueprint $table) {
            $table->id('id_lote');
            $table->unsignedBigInteger('id_producto');
            $table->string('numero_lote');
            $table->date('fecha_caducidad')->nullable();
            $table->timestamps();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });

        // 2. Series
        Schema::create('series', function (Blueprint $table) {
            $table->id('id_serie');
            $table->unsignedBigInteger('id_producto');
            $table->string('numero_serie');
            $table->string('estado')->default('disponible'); // disponible, vendido, bajas
            $table->timestamps();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });

        // 3. Stock
        Schema::create('stock', function (Blueprint $table) {
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_ubicacion');
            $table->decimal('cantidad_actual', 18, 4)->default(0);
            $table->decimal('cantidad_reservada', 18, 4)->default(0);
            $table->decimal('stock_minimo', 18, 4)->default(0);
            $table->decimal('stock_maximo', 18, 4)->default(0);
            $table->primary(['id_producto', 'id_ubicacion']);
            $table->timestamps();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicaciones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock');
        Schema::dropIfExists('series');
        Schema::dropIfExists('lotes');
    }
};

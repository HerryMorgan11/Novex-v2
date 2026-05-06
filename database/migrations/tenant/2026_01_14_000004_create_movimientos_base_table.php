<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Movimiento Inventario
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->dateTime('fecha');
            $table->string('tipo'); // entrada, salida, transferencia, ajuste
            $table->string('referencia')->nullable();
            $table->text('observacion')->nullable();
            $table->string('usuario')->nullable();
            $table->timestamps();
        });

        // 2. Detalle Movimiento Inventario
        Schema::create('detalle_movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_movimiento');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_ubicacion_origen')->nullable();
            $table->unsignedBigInteger('id_ubicacion_destino')->nullable();
            $table->decimal('cantidad', 18, 4);
            $table->decimal('costo_unitario', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_movimiento')->references('id_movimiento')->on('movimientos_inventario')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('productos');
            $table->foreign('id_ubicacion_origen')->references('id_ubicacion')->on('ubicaciones');
            $table->foreign('id_ubicacion_destino')->references('id_ubicacion')->on('ubicaciones');
        });

        // 3. Inventario Fisico
        Schema::create('inventarios_fisicos', function (Blueprint $table) {
            $table->id('id_inventario');
            $table->dateTime('fecha');
            $table->unsignedBigInteger('id_almacen');
            $table->string('estado')->default('pendiente'); // pendiente, finalizado, cancelado
            $table->timestamps();

            $table->foreign('id_almacen')->references('id_almacen')->on('almacenes');
        });

        // 4. Detalle Inventario Fisico
        Schema::create('detalle_inventarios_fisicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inventario');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_ubicacion');
            $table->decimal('cantidad_sistema', 18, 4);
            $table->decimal('cantidad_fisica', 18, 4);
            $table->decimal('diferencia', 18, 4);
            $table->timestamps();

            $table->foreign('id_inventario')->references('id_inventario')->on('inventarios_fisicos')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('productos');
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicaciones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_inventarios_fisicos');
        Schema::dropIfExists('inventarios_fisicos');
        Schema::dropIfExists('detalle_movimientos_inventario');
        Schema::dropIfExists('movimientos_inventario');
    }
};

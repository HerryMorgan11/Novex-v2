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
        // Tabla principal de recepciones
        Schema::create('recepciones', function (Blueprint $table) {
            $table->id('id_recepcion');
            $table->string('codigo_recepcion')->unique(); // REC-2026-00045
            $table->string('nombre_camion')->nullable();
            $table->string('patente', 20)->nullable();

            // Relación con proveedor
            $table->unsignedBigInteger('id_proveedor')->nullable();

            $table->dateTime('fecha_estimada')->nullable();
            $table->dateTime('fecha_recepcion')->nullable();
            $table->string('estado')->default('PENDIENTE'); // PENDIENTE, EN_CURSO, COMPLETADA, CANCELADA
            $table->text('observaciones')->nullable();

            $table->string('creado_por')->nullable();
            $table->dateTime('fecha_creacion')->nullable();

            $table->timestamps();

            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->onDelete('set null');
        });

        // Tabla detalle de productos en la recepción
        Schema::create('recepcion_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_recepcion');
            $table->unsignedBigInteger('id_producto')->nullable();

            // Guardamos el código y nombre original por si el producto no existe en nuestra DB aún
            $table->string('producto_codigo_ref')->nullable();
            $table->string('producto_nombre_ref')->nullable();

            $table->decimal('cantidad_esperada', 18, 4);
            $table->decimal('cantidad_recibida', 18, 4)->default(0);
            $table->string('unidad')->nullable();

            $table->timestamps();

            $table->foreign('id_recepcion')->references('id_recepcion')->on('recepciones')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcion_productos');
        Schema::dropIfExists('recepciones');
    }
};

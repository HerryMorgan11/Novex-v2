<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Expediciones (salidas de inventario hacia reparto o producción)
        Schema::create('expediciones', function (Blueprint $table) {
            $table->id('id_expedicion');
            $table->string('referencia_expedicion')->unique(); // EXP-2026-00001
            $table->string('tipo')->default('reparto'); // reparto | produccion
            $table->string('destino')->nullable();
            $table->string('vehiculo')->nullable();
            $table->string('conductor')->nullable();
            $table->dateTime('fecha_salida')->nullable();
            $table->dateTime('fecha_confirmacion_entrega')->nullable();
            $table->string('estado')->default('preparada'); // preparada | expedida | en_transito | entregada | cancelada
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable(); // quien creó la expedición
            $table->string('token_confirmacion')->nullable()->unique(); // token para endpoint externo
            $table->timestamps();
        });

        // Líneas de expedición (productos incluidos en una expedición)
        Schema::create('lineas_expedicion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_expedicion');
            $table->unsignedBigInteger('id_lote');
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->decimal('cantidad', 18, 4);
            $table->string('unidad')->nullable();
            $table->string('estado')->default('preparada'); // preparada | expedida | entregada
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_expedicion')->references('id_expedicion')->on('expediciones')->onDelete('cascade');
            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('restrict');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lineas_expedicion');
        Schema::dropIfExists('expediciones');
    }
};

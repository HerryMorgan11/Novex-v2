<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Historial de eventos de trazabilidad por lote/producto
        Schema::create('trazabilidad_eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lote');
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->string('tipo_evento'); // recepcion|ubicacion|traslado|produccion|preparado_reparto|expedido|entregado|ajuste|incidencia|bloqueado
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->string('origen_evento')->default('manual'); // manual | api | sistema
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_recepcion')->nullable();
            $table->unsignedBigInteger('id_expedicion')->nullable();
            $table->json('payload')->nullable(); // datos extra del evento
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_evento')->useCurrent();
            $table->timestamps();

            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('set null');
            $table->foreign('id_recepcion')->references('id_recepcion')->on('recepciones')->onDelete('set null');
            $table->foreign('id_expedicion')->references('id_expedicion')->on('expediciones')->onDelete('set null');

            // Índice para consultas frecuentes de historial
            $table->index(['id_lote', 'fecha_evento']);
            $table->index(['id_producto', 'fecha_evento']);
        });

        // Tokens de API para integraciones externas (autenticación simple Bearer)
        Schema::create('api_tokens_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // descripción del sistema externo
            $table->string('token', 64)->unique();
            $table->string('permisos')->default('full'); // full | read | transport_only | delivery_only
            $table->boolean('activo')->default(true);
            $table->timestamp('ultimo_uso')->nullable();
            $table->timestamp('expira_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_tokens_inventario');
        Schema::dropIfExists('trazabilidad_eventos');
    }
};

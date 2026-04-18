<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar estado_validacion a productos (borrador | activo | inactivo)
        Schema::table('productos', function (Blueprint $table) {
            $table->string('estado_validacion')->default('activo')->after('estado');
            $table->text('notas_internas')->nullable()->after('estado_validacion');
        });

        // 2. Agregar estado de ciclo logístico a lotes
        Schema::table('lotes', function (Blueprint $table) {
            $table->string('estado')->default('pending_inbound')->after('fecha_caducidad');
            $table->unsignedBigInteger('id_ubicacion')->nullable()->after('estado');
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicaciones')->onDelete('set null');
        });

        // 3. Extender recepciones (transportes de entrada) con campos logísticos y payload
        Schema::table('recepciones', function (Blueprint $table) {
            $table->string('origen')->nullable()->after('observaciones');
            $table->string('destino')->nullable()->after('origen');
            $table->string('transportista')->nullable()->after('destino');
            $table->json('payload_json')->nullable()->after('transportista');
            $table->string('origen_evento')->default('manual')->after('payload_json'); // manual | api
        });

        // 4. Agregar id_lote a recepcion_productos
        Schema::table('recepcion_productos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lote')->nullable()->after('id_producto');
            $table->string('estado_linea')->default('pendiente')->after('unidad'); // pendiente | recibida | ubicada | incidencia
            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('set null');
        });

        // 5. Extender stock para tracking por lote
        Schema::table('stock', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lote')->nullable()->after('id_ubicacion');
            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('set null');
        });

        // 6. Extender movimientos_inventario con lote y usuario
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lote')->nullable()->after('usuario');
            $table->unsignedBigInteger('id_usuario')->nullable()->after('id_lote');
            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('set null');
        });

        // 7. Agregar id_lote + ubicacion en detalle_movimientos_inventario
        Schema::table('detalle_movimientos_inventario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lote')->nullable()->after('id_producto');
            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('set null');
        });

        // 8. Agregar código de ubicación concatenado y estado a ubicaciones
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->string('codigo_ubicacion')->nullable()->after('posicion');
            $table->boolean('activa')->default(true)->after('codigo_ubicacion');
        });

        // 9. Agregar descripciones a almacenes y zonas
        Schema::table('almacenes', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('responsable');
        });
    }

    public function down(): void
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropColumn(['codigo_ubicacion', 'activa']);
        });

        Schema::table('detalle_movimientos_inventario', function (Blueprint $table) {
            $table->dropForeign(['id_lote']);
            $table->dropColumn('id_lote');
        });

        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->dropForeign(['id_lote']);
            $table->dropColumn(['id_lote', 'id_usuario']);
        });

        Schema::table('stock', function (Blueprint $table) {
            $table->dropForeign(['id_lote']);
            $table->dropColumn('id_lote');
        });

        Schema::table('recepcion_productos', function (Blueprint $table) {
            $table->dropForeign(['id_lote']);
            $table->dropColumn(['id_lote', 'estado_linea']);
        });

        Schema::table('recepciones', function (Blueprint $table) {
            $table->dropColumn(['origen', 'destino', 'transportista', 'payload_json', 'origen_evento']);
        });

        Schema::table('lotes', function (Blueprint $table) {
            $table->dropForeign(['id_ubicacion']);
            $table->dropColumn(['estado', 'id_ubicacion']);
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['estado_validacion', 'notas_internas']);
        });
    }
};

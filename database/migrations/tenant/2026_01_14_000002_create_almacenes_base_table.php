<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Almacen
        Schema::create('almacenes', function (Blueprint $table) {
            $table->id('id_almacen');
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('responsable')->nullable();
            $table->timestamps();
        });

        // 2. Zona
        Schema::create('zonas', function (Blueprint $table) {
            $table->id('id_zona');
            $table->unsignedBigInteger('id_almacen');
            $table->string('nombre');
            $table->timestamps();

            $table->foreign('id_almacen')->references('id_almacen')->on('almacenes')->onDelete('cascade');
        });

        // 3. Estanteria
        Schema::create('estanterias', function (Blueprint $table) {
            $table->id('id_estanteria');
            $table->unsignedBigInteger('id_almacen');
            $table->unsignedBigInteger('id_zona')->nullable();
            $table->string('codigo');
            $table->timestamps();

            $table->foreign('id_almacen')->references('id_almacen')->on('almacenes')->onDelete('cascade');
            $table->foreign('id_zona')->references('id_zona')->on('zonas')->onDelete('cascade');
        });

        // 4. Ubicacion
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id('id_ubicacion');
            $table->unsignedBigInteger('id_estanteria');
            $table->string('pasillo')->nullable();
            $table->string('nivel')->nullable();
            $table->string('posicion')->nullable();
            $table->integer('capacidad')->nullable();
            $table->timestamps();

            $table->foreign('id_estanteria')->references('id_estanteria')->on('estanterias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
        Schema::dropIfExists('estanterias');
        Schema::dropIfExists('zonas');
        Schema::dropIfExists('almacenes');
    }
};

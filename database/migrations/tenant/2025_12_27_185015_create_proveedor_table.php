<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('nombre');
            $table->string('apellido')->nullable();
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('nombre_empresa')->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();
        });

        // Relation table for products and providers (Many to Many is better for ERP)
        Schema::create('producto_proveedores', function (Blueprint $table) {
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_proveedor');
            $table->decimal('ultimo_costo', 18, 2)->nullable();
            $table->primary(['id_producto', 'id_proveedor']);
            $table->timestamps();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_proveedores');
        Schema::dropIfExists('proveedores');
    }
};

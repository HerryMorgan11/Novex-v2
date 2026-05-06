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
        // 1. Unidad de Medida
        Schema::create('unidades_medida', function (Blueprint $table) {
            $table->id('id_unidad');
            $table->string('nombre', 255);
            $table->string('abreviatura', 10);
            $table->decimal('factor_conversion', 18, 4)->default(1);
            $table->timestamps();
        });

        // 2. Categoría Producto
        Schema::create('categorias_producto', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 255);
            $table->unsignedBigInteger('id_categoria_padre')->nullable();
            $table->timestamps();

            $table->foreign('id_categoria_padre')->references('id_categoria')->on('categorias_producto')->onDelete('cascade');
        });

        // 3. Atributo Producto
        Schema::create('atributos_producto', function (Blueprint $table) {
            $table->id('id_atributo');
            $table->string('nombre');
            $table->string('tipo_dato');
            $table->timestamps();
        });

        // 4. Producto
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('codigo_barras')->unique()->nullable();
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_unidad_medida');
            $table->decimal('costo', 18, 2)->default(0);
            $table->decimal('precio_referencia', 18, 2)->default(0);
            $table->string('estado')->default('activo');
            $table->timestamps();

            $table->foreign('id_categoria')->references('id_categoria')->on('categorias_producto');
            $table->foreign('id_unidad_medida')->references('id_unidad')->on('unidades_medida');
        });

        // 5. Producto Atributo
        Schema::create('producto_atributos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_atributo');
            $table->text('valor');
            $table->primary(['id_producto', 'id_atributo']);
            $table->timestamps();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            $table->foreign('id_atributo')->references('id_atributo')->on('atributos_producto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_atributos');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('atributos_producto');
        Schema::dropIfExists('categorias_producto');
        Schema::dropIfExists('unidades_medida');
    }
};

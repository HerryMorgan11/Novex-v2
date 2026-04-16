<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuloInventario\Http\Controllers\ModuloInventarioController;
use Modules\ModuloInventario\Http\Controllers\CategoriasController;
use Modules\ModuloInventario\Http\Controllers\EstanteriasController;
use Modules\ModuloInventario\Http\Controllers\NaveController;
use Modules\ModuloInventario\Http\Controllers\recepcionController;

Route::prefix('dashboard/modulo-inventario')->group(function () {
    Route::get('/', [ModuloInventarioController::class, 'index'])->name('inventario.index');
    Route::get('/productos/create', [ModuloInventarioController::class, 'create'])->name('inventario.productos.create');
    Route::post('/productos', [ModuloInventarioController::class, 'store'])->name('inventario.productos.store');
    
    // Categorías
    Route::prefix('categorias')->group(function () {
        Route::get('/crear', [CategoriasController::class, 'create'])->name('inventario.categorias.crear');
        Route::post('/', [CategoriasController::class, 'store'])->name('inventario.categorias.store');
    });

    // Estanterías
    Route::prefix('estanterias')->group(function () {
        Route::get('/almacen/{id}', [EstanteriasController::class, 'estanteriasPorAlmacen'])->name('inventario.estanterias.por_almacen');
        Route::post('/', [EstanteriasController::class, 'store'])->name('inventario.estanterias.store');
    });

    // Almacenes
    Route::prefix('almacenes')->group(function () {
        Route::get('/', [NaveController::class, 'index'])->name('inventario.almacenes.index');
        Route::get('/crear', [NaveController::class, 'create'])->name('inventario.almacenes.crear');
        Route::post('/', [NaveController::class, 'store'])->name('inventario.almacenes.store');
    });

    // Recepciones
    Route::prefix('recepciones')->group(function () {
        Route::get('/', [recepcionController::class, 'index'])->name('inventario.recepciones.index');
        Route::get('/json', [recepcionController::class, 'recepcionJSON'])->name('inventario.recepciones.json');
        Route::get('/{id}', [recepcionController::class, 'show'])->name('inventario.recepciones.show');
        Route::post('/', [recepcionController::class, 'store'])->name('inventario.recepciones.store');
    });
});

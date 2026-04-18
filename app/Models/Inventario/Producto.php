<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\ProductoValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    protected $table = 'productos';

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'sku',
        'codigo_barras',
        'id_categoria',
        'id_unidad_medida',
        'costo',
        'precio_referencia',
        'estado',
        'estado_validacion',
        'notas_internas',
    ];

    protected $casts = [
        'estado_validacion' => ProductoValidacion::class,
        'costo' => 'decimal:2',
        'precio_referencia' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class, 'id_categoria', 'id_categoria');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida', 'id_unidad');
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'id_producto', 'id_producto');
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'id_producto', 'id_producto');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'id_producto', 'id_producto')
            ->orderByDesc('fecha');
    }

    /** Stock total físico disponible sumando todos los lotes activos */
    public function stockDisponibleTotal(): float
    {
        return (float) $this->stock()
            ->sum(DB::raw('cantidad_actual - cantidad_reservada'));
    }

    public function esBorrador(): bool
    {
        return $this->estado_validacion === ProductoValidacion::Borrador;
    }
}

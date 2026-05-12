<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\ProductoValidacion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Modelo que representa un producto del inventario.
 *
 * @property int $id_producto
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $sku
 * @property string|null $codigo_barras
 * @property int|null $id_categoria
 * @property int|null $id_unidad_medida
 * @property float $costo
 * @property float|null $precio_referencia
 * @property string $estado
 * @property ProductoValidacion $estado_validacion
 * @property string|null $notas_internas
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CategoriaProducto|null $categoria
 * @property-read UnidadMedida|null $unidadMedida
 * @property-read Collection<int, Lote> $lotes
 * @property-read Collection<int, Stock> $stock
 * @property-read Collection<int, Movimiento> $movimientos
 */
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

    /**
     * Obtiene la categoría del producto.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Obtiene la unidad de medida del producto.
     */
    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida', 'id_unidad');
    }

    /**
     * Obtiene los lotes asociados al producto.
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'id_producto', 'id_producto');
    }

    /**
     * Obtiene los registros de stock del producto.
     */
    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'id_producto', 'id_producto');
    }

    /**
     * Obtiene los movimientos del producto, ordenados por fecha descendente.
     */
    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class, 'id_producto', 'id_producto')
            ->orderByDesc('fecha');
    }

    /**
     * Calcula el stock total físico disponible sumando todos los lotes activos.
     */
    public function stockDisponibleTotal(): float
    {
        return (float) $this->stock()
            ->sum(DB::raw('cantidad_actual - cantidad_reservada'));
    }

    /**
     * Determina si el producto está en estado borrador.
     */
    public function esBorrador(): bool
    {
        return $this->estado_validacion === ProductoValidacion::Borrador;
    }
}

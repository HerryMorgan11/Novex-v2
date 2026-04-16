<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductoInventario extends Model
{
    use HasFactory;

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
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'id_categoria', 'id_categoria');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida', 'id_unidad');
    }

    public function proveedores()
    {
        return $this->belongsToMany(ProveedorInventario::class, 'producto_proveedores', 'id_producto', 'id_proveedor')
            ->withPivot('ultimo_costo')
            ->withTimestamps();
    }
}

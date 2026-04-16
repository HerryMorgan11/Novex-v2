<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProveedorInventario extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'nombre_empresa',
        'direccion',
    ];

    public function productos()
    {
        return $this->belongsToMany(ProductoInventario::class, 'producto_proveedores', 'id_proveedor', 'id_producto')
            ->withPivot('ultimo_costo')
            ->withTimestamps();
    }
}

<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
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

    public function transportes(): HasMany
    {
        return $this->hasMany(Transporte::class, 'id_proveedor', 'id_proveedor');
    }
}

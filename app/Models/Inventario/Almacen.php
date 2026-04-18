<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Almacen extends Model
{
    protected $table = 'almacenes';

    protected $primaryKey = 'id_almacen';

    protected $fillable = ['nombre', 'direccion', 'responsable', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function zonas(): HasMany
    {
        return $this->hasMany(Zona::class, 'id_almacen', 'id_almacen');
    }
}

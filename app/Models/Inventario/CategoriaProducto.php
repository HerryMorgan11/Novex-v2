<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    protected $table = 'categorias_producto';

    protected $primaryKey = 'id_categoria';

    protected $fillable = ['nombre', 'id_categoria_padre'];

    public function padre()
    {
        return $this->belongsTo(self::class, 'id_categoria_padre', 'id_categoria');
    }

    public function hijos()
    {
        return $this->hasMany(self::class, 'id_categoria_padre', 'id_categoria');
    }
}

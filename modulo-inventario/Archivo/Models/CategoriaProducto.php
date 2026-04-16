<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriaProducto extends Model
{
    use HasFactory;

    protected $table = 'categorias_producto';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
        'id_categoria_padre',
    ];

    public function padre()
    {
        return $this->belongsTo(CategoriaProducto::class, 'id_categoria_padre', 'id_categoria');
    }

    public function subcategorias()
    {
        return $this->hasMany(CategoriaProducto::class, 'id_categoria_padre', 'id_categoria');
    }
}

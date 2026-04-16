<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recepcion extends Model
{
    use HasFactory;

    protected $table = 'recepciones';
    protected $primaryKey = 'id_recepcion';

    protected $fillable = [
        'codigo_recepcion',
        'nombre_camion',
        'patente',
        'id_proveedor',
        'fecha_estimada',
        'fecha_recepcion',
        'estado',
        'observaciones',
        'creado_por',
        'fecha_creacion'
    ];

    protected $casts = [
        'fecha_estimada' => 'datetime',
        'fecha_recepcion' => 'datetime',
        'fecha_creacion' => 'datetime',
    ];

    /**
     * Relación con los productos de la recepción
     */
    public function productos()
    {
        return $this->hasMany(RecepcionProducto::class, 'id_recepcion', 'id_recepcion');
    }

    /**
     * Relación con el proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(ProveedorInventario::class, 'id_proveedor', 'id_proveedor');
    }
}

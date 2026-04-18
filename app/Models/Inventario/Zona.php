<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zona extends Model
{
    protected $table = 'zonas';

    protected $primaryKey = 'id_zona';

    protected $fillable = ['id_almacen', 'nombre'];

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    public function estanterias(): HasMany
    {
        return $this->hasMany(Estanteria::class, 'id_zona', 'id_zona');
    }
}

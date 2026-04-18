<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class ApiTokenInventario extends Model
{
    protected $table = 'api_tokens_inventario';

    protected $fillable = [
        'nombre',
        'token',
        'permisos',
        'activo',
        'ultimo_uso',
        'expira_en',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_uso' => 'datetime',
        'expira_en' => 'datetime',
    ];

    protected $hidden = ['token'];

    public function esValido(): bool
    {
        return $this->activo
            && ($this->expira_en === null || $this->expira_en->isFuture());
    }
}

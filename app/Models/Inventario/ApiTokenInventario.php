<?php

namespace App\Models\Inventario;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiTokenInventario extends Model
{
    protected $connection = 'tenant';

    protected $table = 'api_tokens_inventario';

    protected $fillable = [
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function esValido(): bool
    {
        return $this->activo
            && ($this->expira_en === null || $this->expira_en->isFuture());
    }
}

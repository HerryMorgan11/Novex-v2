<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa un proveedor de mercancía.
 *
 * @property int $id_proveedor
 * @property string $nombre
 * @property string|null $apellido
 * @property string|null $email
 * @property string|null $telefono
 * @property string|null $nombre_empresa
 * @property string|null $direccion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Transporte> $transportes
 */
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

    /**
     * Obtiene los transportes (recepciones) del proveedor.
     */
    public function transportes(): HasMany
    {
        return $this->hasMany(Transporte::class, 'id_proveedor', 'id_proveedor');
    }
}

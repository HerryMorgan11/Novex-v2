<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * Modelo que representa una cuenta social vinculada a un usuario (Google, etc.).
 *
 * @property int $id
 * @property string $user_id
 * @property string $provider
 * @property string $provider_user_id
 * @property string|null $email
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property Carbon|null $token_expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class SocialAccount extends Model
{
    use CentralConnection;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'email',
        'access_token',
        'refresh_token',
        'token_expires_at',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    /**
     * Obtiene el usuario propietario de la cuenta social.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

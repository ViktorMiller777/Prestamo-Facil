<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'usuarios';

    protected $fillable = [
        'persona_id',
        'sucursal_id',
        'role_id',
        'email',
        'password'
    ];

    public function persona(): BelongsTo{
        return $this->belongsTo(Persona::class,'persona_id');
    }

    public function sucursal(): BelongsTo{
        return $this->belongsTo(Sucursal::class,'sucursal_id');
    }

    public function role(): BelongsTo{
        return $this->belongsTo(Role::class,'role_id');
    }

    public function distribuidora(): HasOne{
        return $this->hasOne(Distribuidora::class,'usuario_id');
    }

    public function notificacion(): HasMany{
        return $this->hasMany(Notificacion::class,'usuario_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

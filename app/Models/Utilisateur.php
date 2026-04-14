<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Utilisateur extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'uuid',
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'etat',
        'photo_url',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'derniere_connexion_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    // Relations
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_utilisateur');
    }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }

    // Check if user has a specific role
    public function hasRole($role)
    {
        return $this->roles()->where('code', $role)->exists();
    }
}

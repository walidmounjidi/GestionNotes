<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class)->orderBy('order');
    }

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Utilisateur::class, 'teacher_specialization');
    }

    public function etudiants(): HasManyThrough
    {
        return $this->hasManyThrough(Etudiant::class, Classe::class, 'specialization_id', 'classe_id', 'id', 'id');
    }

    public function getStudentsCountAttribute(): int
    {
        return $this->etudiants()->count();
    }
}

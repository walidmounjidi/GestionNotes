<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiants';

    protected $fillable = [
        'utilisateur_id',
        'matricule',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'nom_arabe',
        'prenom_arabe',
        'classe_id',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    // Relations
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    // Accessor for full name
    public function getFullNameAttribute(): string
    {
        return $this->utilisateur->nom . ' ' . $this->utilisateur->prenom;
    }
}

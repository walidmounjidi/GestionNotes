<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    use HasFactory;

    protected $table = 'matieres';

    protected $fillable = [
        'code',
        'libelle',
        'libelle_arabe',
        'coefficient',
        'credits',
        'description',
    ];

    // Relations
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'matiere_classe');
    }
}

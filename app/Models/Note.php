<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';

    protected $fillable = [
        'etudiant_id',
        'evaluation_id',
        'note',
        'observation',
        'utilisateur_saisie_id',
    ];

    protected $casts = [
        'note' => 'decimal:2',
        'date_saisie' => 'datetime',
    ];

    // Relations
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function utilisateurSaisie(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_saisie_id');
    }
}

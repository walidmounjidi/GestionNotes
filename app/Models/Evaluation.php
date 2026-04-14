<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';

    protected $fillable = [
        'matiere_id',
        'classe_id',
        'type',
        'description',
        'date_evaluation',
        'note_max',
        'coefficient',
        'session',
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'note_max' => 'decimal:2',
        'coefficient' => 'decimal:2',
    ];

    // Relations
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}

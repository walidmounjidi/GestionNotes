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
        'created_by',
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'note_max' => 'decimal:2',
        'coefficient' => 'decimal:2',
    ];

    // Scopes
    public function scopeF1($query)
    {
        return $query->where('type', 'f1');
    }

    public function scopeF2($query)
    {
        return $query->where('type', 'f2');
    }

    public function scopeF3($query)
    {
        return $query->where('type', 'f3');
    }

    public function scopeFinal($query)
    {
        return $query->where('type', 'final');
    }

    public function scopeDevoir($query)
    {
        return $query->where('type', 'devoir');
    }

    public function scopeExamen($query)
    {
        return $query->where('type', 'examen');
    }

    public function isEditable(): bool
    {
        return in_array($this->type, ['f1', 'f2', 'f3', 'devoir']);
    }

    public function isReadOnly(): bool
    {
        return in_array($this->type, ['final', 'examen']);
    }

    // Relations
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Utilisateur::class, 'created_by');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}

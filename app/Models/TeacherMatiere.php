<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherMatiere extends Model
{
    protected $table = 'teacher_matiere';

    protected $fillable = [
        'utilisateur_id',
        'matiere_id',
        'classe_id',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}

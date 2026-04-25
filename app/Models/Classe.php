<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'code',
        'libelle',
        'niveau',
        'annee_scolaire',
        'description',
        'specialization_id',
        'level_id',
        'student_count',
        'max_students',
    ];

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function matieres(): BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'matiere_classe');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Utilisateur::class, 'teacher_classe');
    }

    public function isFull(): bool
    {
        return $this->student_count >= $this->max_students;
    }

    public function hasSpace(): bool
    {
        return $this->student_count < $this->max_students;
    }

    public function availableSlots(): int
    {
        return $this->max_students - $this->student_count;
    }

    public function incrementStudentCount(): void
    {
        $this->increment('student_count');
    }

    public function decrementStudentCount(): void
    {
        if ($this->student_count > 0) {
            $this->decrement('student_count');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($classe) {
            $classe->updateStudentCount();
        });

        static::updated(function ($classe) {
            if ($classe->isDirty('classe_id')) {
                // Handle class change logic if needed
            }
        });
    }

    public function updateStudentCount(): void
    {
        $this->student_count = $this->etudiants()->count();
        $this->save();
    }
}

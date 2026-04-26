<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        'temporary_password',
        'telephone',
        'etat',
        'photo_url',
        'email_verified_at',
    ];

    protected $attributes = [
        'email_locked' => false,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'derniere_connexion_at' => 'datetime',
        'password' => 'hashed',
        'email_locked' => 'boolean',
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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_utilisateur');
    }

    public function etudiant(): HasOne
    {
        return $this->hasOne(Etudiant::class);
    }

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'teacher_specialization');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'teacher_classe');
    }

    public function matieres(): BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'teacher_matiere')
            ->withPivot('classe_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'teacher_subject');
    }

    public function matiereClasses(): HasMany
    {
        return $this->hasMany(\App\Models\TeacherMatiere::class, 'utilisateur_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles()->where('code', $role)->exists();
        }

        return $this->roles()->whereIn('code', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function getPrimaryRoleAttribute(): ?string
    {
        $roleOrder = ['admin', 'manager', 'teacher', 'student'];
        foreach ($roleOrder as $role) {
            if ($this->hasRole($role)) {
                return $role;
            }
        }

        return null;
    }

    public function assignRole(Role $role): void
    {
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    public static function generateTeacherEmail(): string
    {
        do {
            $randomNumber = random_int(1000000, 9999999);
            $email = "PR{$randomNumber}@etu-note.ma";
        } while (self::where('email', $email)->exists());

        return $email;
    }

    public static function generateStudentEmail(): string
    {
        do {
            $randomNumber = random_int(1000000, 9999999);
            $email = "TE{$randomNumber}@etu-note.ma";
        } while (self::where('email', $email)->exists());

        return $email;
    }

    public static function generateSecurePassword(int $length = 12): string
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '!@#$%';

        $allChars = $lowercase . $uppercase . $numbers . $special;

        $password = $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }

    public function getTeacherScope(): \Illuminate\Database\Eloquent\Builder
    {
        return self::whereHas('roles', fn ($q) => $q->where('code', 'teacher'));
    }

    public function getStudentScope(): \Illuminate\Database\Eloquent\Builder
    {
        return self::whereHas('roles', fn ($q) => $q->where('code', 'student'));
    }

    public function canEditEmail(): bool
    {
        return ! (bool) $this->getAttribute('email_locked');
    }

    public function lockEmail(): void
    {
        $this->setAttribute('email_locked', true);
        $this->save();
    }

    public function isEmailLocked(): bool
    {
        return (bool) $this->getAttribute('email_locked');
    }
}

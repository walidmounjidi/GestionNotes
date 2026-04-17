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
            $randomNumber = random_int(100000, 999999);
            $email = "PR{$randomNumber}@etu-not.ma";
        } while (self::where('email', $email)->exists());

        return $email;
    }

    public static function generateStudentEmail(): string
    {
        do {
            $randomNumber = random_int(100000, 999999);
            $email = "ET{$randomNumber}@etu-not.ma";
        } while (self::where('email', $email)->exists());

        return $email;
    }

    public static function generateSecurePassword(int $length = 12): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';

        return substr(str_shuffle($chars), 0, $length);
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
        return ! $this->email_locked && ! $this->hasRole(['teacher', 'student']);
    }

    public function lockEmail(): void
    {
        $this->email_locked = true;
        $this->save();
    }
}

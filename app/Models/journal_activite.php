<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalActivite extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'action',
        'objet_type',
        'objet_id',
        'ip',
        'user_agent',
        'donnees_avant',
        'donnees_apres'
    ];

    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PieceJointe extends Model
{
    use HasFactory;

    protected $fillable = [
        'objet_type',
        'objet_id',
        'nom_fichier',
        'chemin',
        'mime',
        'taille',
        'uploaded_by'
    ];

    public function objet()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->belongsTo(Utilisateur::class, 'uploaded_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adresse extends Model
{
    use HasFactory;

    protected $fillable = [
        'pays',
        'ville',
        'quartier',
        'rue',
        'code_postal',
        'latitude',
        'longitude'
    ];
}
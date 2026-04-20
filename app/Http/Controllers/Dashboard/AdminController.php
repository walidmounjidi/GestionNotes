<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Utilisateur;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers' => Utilisateur::whereHas('roles', function ($q) {
                $q->where('code', 'teacher');
            })->count(),
            'total_students' => Etudiant::count(),
            'total_classes' => Classe::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }
}

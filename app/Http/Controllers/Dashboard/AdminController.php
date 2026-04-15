<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => Utilisateur::count(),
            'total_students' => Etudiant::count(),
            'total_classes' => Classe::count(),
            'total_matieres' => Matiere::count(),
            'total_evaluations' => Evaluation::count(),
            'total_notes' => Note::count(),
            'total_teachers' => Utilisateur::whereHas('roles', function ($q) {
                $q->where('code', 'teacher');
            })->count(),
            'total_managers' => Utilisateur::whereHas('roles', function ($q) {
                $q->where('code', 'manager');
            })->count(),
        ];

        $recent_notes = Note::with(['etudiant.utilisateur', 'evaluation.matiere', 'evaluation.classe'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recent_evaluations = Evaluation::with(['matiere', 'classe'])
            ->orderBy('date_evaluation', 'desc')
            ->take(5)
            ->get();

        $recent_users = Utilisateur::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $moyenne_generale = Note::avg('note');

        return view('dashboard.admin', compact(
            'stats',
            'recent_notes',
            'recent_evaluations',
            'recent_users',
            'moyenne_generale'
        ));
    }
}

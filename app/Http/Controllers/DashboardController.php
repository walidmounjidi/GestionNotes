<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Note;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'total_etudiants' => Etudiant::count(),
            'total_matieres' => Matiere::count(),
            'total_classes' => Classe::count(),
            'total_evaluations' => Evaluation::count(),
        ];

        // Get recent grades
        $recent_notes = Note::with(['etudiant.utilisateur', 'evaluation.matiere'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Calculate average grade
        $moyenne_generale = Note::avg('note');
        
        // Get grades by evaluation type
        $notes_par_type = Note::with('evaluation')
            ->get()
            ->groupBy('evaluation.type')
            ->map(function ($notes) {
                return $notes->avg('note');
            });

        return view('dashboard', compact('stats', 'recent_notes', 'moyenne_generale', 'notes_par_type'));
    }
}

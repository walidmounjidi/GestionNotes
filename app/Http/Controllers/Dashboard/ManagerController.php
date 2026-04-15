<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Evaluation;
use App\Models\Note;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Etudiant::count(),
            'total_classes' => Classe::count(),
            'total_matieres' => Matiere::count(),
            'total_evaluations' => Evaluation::count(),
            'total_notes' => Note::count(),
        ];

        $recent_notes = Note::with(['etudiant.utilisateur', 'evaluation.matiere', 'evaluation.classe'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recent_evaluations = Evaluation::with(['matiere', 'classe'])
            ->orderBy('date_evaluation', 'desc')
            ->take(5)
            ->get();

        $notes_by_classe = Classe::withCount('etudiants')
            ->with(['etudiants.notes'])
            ->get()
            ->map(function ($classe) {
                $avg = $classe->etudiants->flatMap->notes->avg('note');
                return [
                    'classe' => $classe->libelle,
                    'etudiants_count' => $classe->etudiants_count,
                    'moyenne' => $avg ? round($avg, 2) : 0,
                ];
            });

        $moyenne_generale = Note::avg('note');

        return view('dashboard.manager', compact(
            'stats',
            'recent_notes',
            'recent_evaluations',
            'notes_by_classe',
            'moyenne_generale'
        ));
    }
}

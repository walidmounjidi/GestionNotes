<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Evaluation;
use App\Models\Note;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $stats = [
            'total_etudiants' => Etudiant::count(),
            'total_classes' => Classe::count(),
            'total_matieres' => Matiere::count(),
            'total_evaluations' => Evaluation::count(),
            'total_notes' => Note::count(),
        ];

        $recent_notes = Note::with(['etudiant.utilisateur', 'evaluation.matiere'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $moyenne_generale = Note::avg('note');

        return view('dashboard', compact('stats', 'recent_notes', 'moyenne_generale'));
    }
}

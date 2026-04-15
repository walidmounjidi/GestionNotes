<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_evaluations' => Evaluation::count(),
            'total_notes' => Note::count(),
            'pending_validation' => Note::where('valide', false)->count(),
            'validated' => Note::where('valide', true)->count(),
        ];

        $recent_evaluations = Evaluation::with(['matiere', 'classe'])
            ->orderBy('date_evaluation', 'desc')
            ->take(5)
            ->get();

        $unvalidated_notes = Note::with(['etudiant.utilisateur', 'evaluation.matiere', 'evaluation.classe'])
            ->where('valide', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $notes_by_evaluation = Evaluation::with('matiere')
            ->withCount('notes')
            ->with(['notes' => function ($q) {
                $q->selectRaw('evaluation_id, AVG(note) as avg_note, COUNT(*) as count');
                $q->groupBy('evaluation_id');
            }])
            ->get();

        $moyenne_generale = Note::avg('note');

        return view('dashboard.teacher', compact(
            'stats',
            'recent_evaluations',
            'unvalidated_notes',
            'notes_by_evaluation',
            'moyenne_generale'
        ));
    }
}

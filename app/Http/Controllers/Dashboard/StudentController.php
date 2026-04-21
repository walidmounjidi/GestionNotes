<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Evaluation;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (! $etudiant) {
            return view('dashboard.student', [
                'etudiant' => null,
                'myNotes' => collect(),
                'mySubjects' => collect(),
                'moyenne_generale' => null,
                'progressIndicator' => null,
                'upcomingEvaluations' => collect(),
                'error' => 'Profil étudiant non trouvé. Veuillez contacter l\'administrateur.',
            ]);
        }

        $myNotes = Note::with(['evaluation.matiere', 'evaluation.classe'])
            ->where('etudiant_id', $etudiant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $mySubjects = [];
        if ($etudiant->classe_id && $etudiant->classe) {
            $mySubjects = $etudiant->classe->matieres->map(function ($matiere) use ($etudiant) {
                $matiereNotes = Note::where('etudiant_id', $etudiant->id)
                    ->whereHas('evaluation', function ($q) use ($matiere) {
                        $q->where('matiere_id', $matiere->id);
                    })
                    ->get();

                return [
                    'matiere' => $matiere,
                    'average' => $matiereNotes->avg('note') ? round($matiereNotes->avg('note'), 2) : null,
                    'notes_count' => $matiereNotes->count(),
                ];
            });
        }

        $moyenne_generale = Note::where('etudiant_id', $etudiant->id)->avg('note');

        $progressIndicator = null;
        if ($moyenne_generale !== null) {
            if ($moyenne_generale >= 16) {
                $progressIndicator = ['label' => 'Excellent', 'color' => 'emerald'];
            } elseif ($moyenne_generale >= 14) {
                $progressIndicator = ['label' => 'Bien', 'color' => 'blue'];
            } elseif ($moyenne_generale >= 10) {
                $progressIndicator = ['label' => 'Passable', 'color' => 'amber'];
            } else {
                $progressIndicator = ['label' => 'Insuffisant', 'color' => 'red'];
            }
        }

        $upcomingEvaluations = collect();
        if ($etudiant->classe_id) {
            $upcomingEvaluations = Evaluation::with(['matiere', 'classe'])
                ->where('classe_id', $etudiant->classe_id)
                ->where('date_evaluation', '>', now())
                ->orderBy('date_evaluation', 'asc')
                ->take(5)
                ->get();
        }

        return view('dashboard.student', compact(
            'etudiant',
            'myNotes',
            'mySubjects',
            'moyenne_generale',
            'progressIndicator',
            'upcomingEvaluations'
        ));
    }
}

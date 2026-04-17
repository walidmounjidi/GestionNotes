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
            return redirect()->route('welcome')->with('error', 'Profil étudiant non trouvé');
        }

        $recent_notes = Note::with(['evaluation.matiere', 'evaluation.classe'])
            ->where('etudiant_id', $etudiant->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $upcoming_evaluations = Evaluation::with(['matiere', 'classe'])
            ->where('classe_id', $etudiant->classe_id)
            ->where('date_evaluation', '>=', now())
            ->orderBy('date_evaluation', 'asc')
            ->take(5)
            ->get();

        $moyenne_generale = Note::where('etudiant_id', $etudiant->id)->avg('note');

        $notes_by_matiere = Note::with('evaluation.matiere')
            ->where('etudiant_id', $etudiant->id)
            ->get()
            ->groupBy('evaluation.matiere.libelle')
            ->map(function ($notes, $matiereName) {
                return [
                    'count' => $notes->count(),
                    'avg' => round($notes->avg('note'), 2),
                    'max' => $notes->max('note'),
                    'min' => $notes->min('note'),
                ];
            });

        $studentSubjects = [];
        if ($etudiant->classe_id && $etudiant->classe) {
            $studentSubjects = $etudiant->classe->matieres->map(function ($matiere) use ($etudiant) {
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

        $ranking = null;
        if ($etudiant->classe_id) {
            $classe_etudiants = Etudiant::where('classe_id', $etudiant->classe_id)
                ->with('notes')
                ->get()
                ->map(function ($e) {
                    $e->moyenne = $e->notes->avg('note') ?? 0;

                    return $e;
                })
                ->sortByDesc('moyenne')
                ->values();

            $position = $classe_etudiants->search(function ($e) use ($etudiant) {
                return $e->id === $etudiant->id;
            });

            $ranking = [
                'position' => $position !== false ? $position + 1 : null,
                'total' => $classe_etudiants->count(),
            ];
        }

        return view('dashboard.student', compact(
            'etudiant',
            'recent_notes',
            'upcoming_evaluations',
            'moyenne_generale',
            'notes_by_matiere',
            'studentSubjects',
            'ranking'
        ));
    }
}

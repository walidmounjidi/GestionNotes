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

        return view('dashboard.student', compact(
            'etudiant',
            'myNotes',
            'mySubjects',
            'moyenne_generale'
        ));
    }
}

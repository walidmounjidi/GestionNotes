<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            return redirect('/')->with('error', 'Profil étudiant non trouvé');
        }

        $notes = Note::with(['evaluation.matiere', 'evaluation.classe'])
            ->where('etudiant_id', $etudiant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $moyenne = $notes->avg('note');

        $classmates = [];
        if ($etudiant->classe_id) {
            $classmates = \App\Models\Etudiant::where('classe_id', $etudiant->classe_id)
                ->where('id', '!=', $etudiant->id)
                ->with('utilisateur')
                ->get();
        }

        $upcomingEvaluations = Evaluation::where('classe_id', $etudiant->classe_id)
            ->where('date_evaluation', '>=', now())
            ->with(['matiere', 'classe'])
            ->orderBy('date_evaluation')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'etudiant',
            'notes',
            'moyenne',
            'classmates',
            'upcomingEvaluations'
        ));
    }

    public function myGrades()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            return redirect('/')->with('error', 'Profil étudiant non trouvé');
        }

        $notes = Note::with(['evaluation.matiere', 'evaluation.classe'])
            ->where('etudiant_id', $etudiant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.grades', compact('etudiant', 'notes'));
    }

    public function classmates()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant || !$etudiant->classe_id) {
            return redirect()->route('student.dashboard')->with('error', 'Vous n\'avez pas de classe assignée');
        }

        $classmates = \App\Models\Etudiant::where('classe_id', $etudiant->classe_id)
            ->with('utilisateur')
            ->withCount('notes')
            ->get()
            ->map(function ($mate) {
                $mate->average = $mate->notes->avg('note') ?? 0;
                return $mate;
            })
            ->sortByDesc('average');

        return view('student.classmates', compact('etudiant', 'classmates'));
    }
}

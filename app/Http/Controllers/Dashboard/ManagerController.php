<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Evaluation;
use App\Models\Level;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Specialization;
use App\Models\Utilisateur;
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
            'total_teachers' => Utilisateur::whereHas('roles', function ($q) {
                $q->where('code', 'teacher');
            })->count(),
        ];

        $teachers = Utilisateur::whereHas('roles', function ($q) {
            $q->where('code', 'teacher');
        })
            ->with(['specializations', 'classes.level', 'matiereClasses.matiere', 'matiereClasses.classe'])
            ->get()
            ->map(function ($teacher) {
                $classes = $teacher->classes;
                $specializations = $teacher->specializations;

                $studentsCount = $classes->sum(function ($classe) {
                    return $classe->etudiants()->count();
                });

                $classesByLevel = $classes->groupBy(fn ($c) => $c->level->libelle ?? 'Non défini')
                    ->map->count();

                $specializationNames = $specializations->pluck('libelle')->toArray();

                return [
                    'id' => $teacher->id,
                    'name' => $teacher->nom.' '.$teacher->prenom,
                    'email' => $teacher->email,
                    'specializations' => $specializationNames,
                    'students_count' => $studentsCount,
                    'classes_count' => $classes->count(),
                    'classes_by_level' => $classesByLevel,
                    'matieres_count' => $teacher->matiereClasses->count(),
                    'assigned_matieres' => $teacher->matiereClasses,
                ];
            });

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

        $specializations = Specialization::all();
        $levels = Level::all();
        $matieres = Matiere::all();
        $classes = Classe::with(['level', 'specialization'])->get();

        return view('dashboard.manager', compact(
            'stats',
            'teachers',
            'recent_notes',
            'recent_evaluations',
            'notes_by_classe',
            'moyenne_generale',
            'specializations',
            'levels',
            'matieres',
            'classes'
        ));
    }

    public function assignSubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:utilisateurs,id',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $teacher = Utilisateur::findOrFail($request->teacher_id);

        if (! $teacher->isTeacher()) {
            return back()->with('error', 'Cet utilisateur n\'est pas un professeur.');
        }

        $teacher->matiereClasses()->firstOrCreate([
            'matiere_id' => $request->matiere_id,
            'classe_id' => $request->classe_id,
        ]);

        return back()->with('success', 'Matière assignée au professeur avec succès.');
    }

    public function removeSubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:utilisateurs,id',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $teacher = Utilisateur::findOrFail($request->teacher_id);

        $teacher->matiereClasses()
            ->where('matiere_id', $request->matiere_id)
            ->where('classe_id', $request->classe_id)
            ->delete();

        return back()->with('success', 'Matière retirée du professeur avec succès.');
    }
}

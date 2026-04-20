<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers' => Utilisateur::whereHas('roles', function ($q) {
                $q->where('code', 'teacher');
            })->count(),
            'total_students' => Etudiant::count(),
            'total_classes' => Classe::count(),
            'total_matieres' => Matiere::count(),
        ];

        $classesWithoutTeacher = Classe::whereDoesntHave('teachers')
            ->with(['level', 'specialization'])
            ->get();

        $teachers = Utilisateur::whereHas('roles', function ($q) {
            $q->where('code', 'teacher');
        })
            ->with(['specializations', 'classes.level'])
            ->get()
            ->map(function ($teacher) {
                $classes = $teacher->classes;
                $specializations = $teacher->specializations;

                $studentsCount = $classes->sum(function ($classe) {
                    return $classe->etudiants()->count();
                });

                $specializationNames = $specializations->pluck('libelle')->toArray();

                return [
                    'id' => $teacher->id,
                    'name' => $teacher->nom.' '.$teacher->prenom,
                    'email' => $teacher->email,
                    'specializations' => $specializationNames,
                    'students_count' => $studentsCount,
                    'classes_count' => $classes->count(),
                ];
            });

        $matieres = Matiere::all();
        $classes = Classe::with(['level', 'specialization'])->get();

        return view('dashboard.manager', compact(
            'stats',
            'teachers',
            'matieres',
            'classes',
            'classesWithoutTeacher'
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

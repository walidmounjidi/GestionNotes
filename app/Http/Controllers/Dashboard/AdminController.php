<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Utilisateur;
use App\Models\Specialization;
use App\Models\Matiere;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers' => Utilisateur::whereHas('roles', function ($q) {
                $q->where('code', 'teacher');
            })->count(),
            'total_students' => Etudiant::count(),
            'total_classes' => Classe::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    public function assignSubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:utilisateurs,id',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $teacher = Utilisateur::findOrFail($request->teacher_id);
        $matiere = Matiere::findOrFail($request->matiere_id);
        $classe = Classe::findOrFail($request->classe_id);

        // Check if teacher is already assigned to this subject for this class
        $existing = $teacher->matiereClasses()
            ->where('matiere_id', $matiere->id)
            ->where('classe_id', $classe->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Le professeur est déjà assigné à cette matière pour cette classe.');
        }

        // Create the assignment
        \App\Models\TeacherMatiere::create([
            'utilisateur_id' => $teacher->id,
            'matiere_id' => $matiere->id,
            'classe_id' => $classe->id,
        ]);

        return redirect()->back()->with('success', 'Assignation de matière réussie.');
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
            ->detach();

        return redirect()->back()->with('success', 'Assignation de matière supprimée.');
    }
}

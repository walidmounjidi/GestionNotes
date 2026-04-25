<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Specialization;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $classes = $user->classes()->with(['etudiants', 'specialization', 'level'])->get();

        $classesWithStats = $classes->map(function ($classe) {
            return [
                'classe' => $classe,
                'students_count' => $classe->etudiants->count(),
            ];
        });

        $specializations = $user->specializations;

        return view('teacher.dashboard', compact('classes', 'classesWithStats', 'specializations'));
    }

    public function index()
    {
        $teachers = Utilisateur::whereHas('roles', function ($q) {
            $q->where('code', 'teacher');
        })->with(['specializations', 'classes'])->get();

        return view('teacher.index', compact('teachers'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        $classes = Classe::with(['specialization', 'level'])->get();

        return view('teacher.create', compact('specializations', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'specializations' => 'array',
            'classes' => 'array',
        ]);

        $email = Utilisateur::generateTeacherEmail();
        $password = Utilisateur::generateSecurePassword();

        $teacher = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $email,
            'password' => bcrypt($password),
            'temporary_password' => $password,
            'etat' => 'actif',
            'email_verified_at' => now(),
            'email_locked' => true,
        ]);

        $teacherRole = \App\Models\Role::where('code', 'teacher')->first();
        if ($teacherRole) {
            $teacher->roles()->sync([$teacherRole->id]);
        }

        if ($request->has('specializations')) {
            $teacher->specializations()->sync($request->specializations);
        }

        if ($request->has('classes')) {
            $teacher->classes()->sync($request->classes);
        }

        return redirect()->route('teacher.index')->with([
            'success' => 'Professeur créé avec succès.',
            'credentials' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);
    }

    public function show(Utilisateur $teacher)
    {
        $teacher->load(['specializations', 'classes.etudiants']);

        return view('teacher.show', compact('teacher'));
    }

    public function edit(Utilisateur $teacher)
    {
        $specializations = Specialization::all();
        $classes = Classe::with(['specialization', 'level'])->get();
        $teacher->load(['specializations', 'classes']);

        return view('teacher.edit', compact('teacher', 'specializations', 'classes'));
    }

    public function update(Request $request, Utilisateur $teacher)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'specializations' => 'array',
            'classes' => 'array',
        ]);

        $teacher->update($request->only(['nom', 'prenom']));

        if ($request->has('specializations')) {
            $teacher->specializations()->sync($request->specializations);
        }

        if ($request->has('classes')) {
            $teacher->classes()->sync($request->classes);
        }

        return redirect()->route('teacher.index')->with('success', 'Professeur mis à jour avec succès.');
    }

    public function destroy(Utilisateur $teacher)
    {
        $teacher->delete();
        return redirect()->route('teacher.index')->with('success', 'Professeur supprimé avec succès.');
    }
}

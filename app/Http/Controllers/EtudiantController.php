<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $query = Etudiant::with(['utilisateur', 'classe']);

        if ($request->search) {
            $query->whereHas('utilisateur', function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                    ->orWhere('prenom', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            })->orWhere('matricule', 'like', "%{$request->search}%");
        }

        if ($request->classe_id) {
            $query->where('classe_id', $request->classe_id);
        }

        $etudiants = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('etudiants.index', compact('etudiants'));
    }

    public function create()
    {
        $classes = Classe::with(['level', 'specialization'])->get();

        return view('etudiants.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|unique:etudiants,matricule',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'nom_arabe' => 'nullable|string|max:255',
            'prenom_arabe' => 'nullable|string|max:255',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $email = Utilisateur::generateStudentEmail();
        $password = Utilisateur::generateSecurePassword();

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $email,
            'password' => Hash::make($password),
            'email_locked' => true,
        ]);

        $studentRole = \App\Models\Role::where('code', 'student')->first();
        if ($studentRole) {
            $utilisateur->roles()->sync([$studentRole->id]);
        }

        $etudiant = Etudiant::create([
            'utilisateur_id' => $utilisateur->id,
            'matricule' => $request->matricule,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'sexe' => $request->sexe,
            'nom_arabe' => $request->nom_arabe,
            'prenom_arabe' => $request->prenom_arabe,
            'classe_id' => $request->classe_id,
        ]);

        return redirect()->route('etudiants.index')->with([
            'success' => 'Étudiant créé avec succès.',
            'credentials' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);
    }

    public function show(Etudiant $etudiant)
    {
        $etudiant->load(['utilisateur', 'classe', 'notes.evaluation.matiere']);

        return view('etudiants.show', compact('etudiant'));
    }

    public function edit(Etudiant $etudiant)
    {
        $etudiant->load('utilisateur');

        return view('etudiants.edit', compact('etudiant'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => ['required', 'string', Rule::unique('etudiants')->ignore($etudiant->id)],
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'nom_arabe' => 'nullable|string|max:255',
            'prenom_arabe' => 'nullable|string|max:255',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $etudiant->utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
        ]);

        $etudiant->update([
            'matricule' => $request->matricule,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'sexe' => $request->sexe,
            'nom_arabe' => $request->nom_arabe,
            'prenom_arabe' => $request->prenom_arabe,
            'classe_id' => $request->classe_id,
        ]);

        return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour avec succès');
    }

    public function destroy(Etudiant $etudiant)
    {
        $user = request()->user();
        if (!$user->hasRole(['admin'])) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas la permission de supprimer des étudiants.');
        }

        $utilisateur = $etudiant->utilisateur;
        $etudiant->delete();
        $utilisateur->delete();

        return redirect()->route('etudiants.index')->with('success', 'Étudiant supprimé avec succès');
    }
}

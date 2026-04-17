<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UtilisateurController extends Controller
{
    public function index(Request $request)
    {
        $query = Utilisateur::with('roles');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('code', $request->role);
            });
        }

        $utilisateurs = $query->orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::all();

        return view('utilisateurs.index', compact('utilisateurs', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('utilisateurs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:utilisateurs',
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'roles' => 'required|array',
        ]);

        $roles = Role::whereIn('id', $request->roles)->pluck('code')->toArray();
        
        $email = $request->email;
        $password = $request->password;
        $emailLocked = false;

        if (in_array('teacher', $roles)) {
            $email = $email ?: Utilisateur::generateTeacherEmail();
            $password = $password ?: Utilisateur::generatePassword();
            $emailLocked = true;
        } elseif (in_array('student', $roles)) {
            $email = $email ?: Utilisateur::generateStudentEmail();
            $password = $password ?: Utilisateur::generatePassword();
            $emailLocked = true;
        } else {
            $password = $password ?: Utilisateur::generatePassword();
        }

        $utilisateur = Utilisateur::create([
            'uuid' => Str::uuid()->toString(),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $email,
            'password' => Hash::make($password),
            'telephone' => $request->telephone,
            'etat' => 'actif',
            'email_verified_at' => now(),
            'email_locked' => $emailLocked,
        ]);

        $utilisateur->roles()->sync($request->roles);

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.')
            ->with('credentials', $credentials);
    }

    public function show(Utilisateur $utilisateur)
    {
        $utilisateur->load('roles');
        return view('utilisateurs.show', compact('utilisateur'));
    }

    public function edit(Utilisateur $utilisateur)
    {
        $roles = Role::all();
        $utilisateur->load('roles');
        return view('utilisateurs.edit', compact('utilisateur', 'roles'));
    }

    public function update(Request $request, Utilisateur $utilisateur)
    {
        $validationRules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'roles' => 'required|array',
        ];

        if ($utilisateur->canEditEmail()) {
            $validationRules['email'] = 'required|string|email|max:255|unique:utilisateurs,email,' . $utilisateur->id;
        }

        $request->validate($validationRules);

        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
        ];

        if ($utilisateur->canEditEmail()) {
            $data['email'] = $request->email;
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $utilisateur->update($data);
        $utilisateur->roles()->sync($request->roles);

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(Utilisateur $utilisateur)
    {
        $utilisateur->roles()->detach();
        $utilisateur->delete();

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}

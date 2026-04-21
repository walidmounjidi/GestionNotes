<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    public function index(Request $request)
    {
        $query = Classe::query();
        
        if ($request->search) {
            $query->where('libelle', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
        }
        
        if ($request->niveau) {
            $query->where('niveau', $request->niveau);
        }
        
        $classes = $query->orderBy('annee_scolaire', 'desc')->orderBy('niveau')->paginate(10);
        
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:classes,code',
            'libelle' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_scolaire' => 'required|integer|min:2020|max:2100',
            'description' => 'nullable|string',
        ]);

        Classe::create($request->all());

        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès');
    }

    public function show(Classe $classe)
    {
        $classe->load(['etudiants.utilisateur', 'matieres', 'evaluations']);
        return view('classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        return view('classes.edit', compact('classe'));
    }

    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'code' => ['required', 'string', Rule::unique('classes')->ignore($classe->id)],
            'libelle' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_scolaire' => 'required|integer|min:2020|max:2100',
            'description' => 'nullable|string',
        ]);

        $classe->update($request->all());

        return redirect()->route('classes.index')->with('success', 'Classe mise à jour avec succès');
    }

    public function destroy(Classe $classe)
    {
        $user = request()->user();
        if (!$user->hasRole(['admin', 'manager'])) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas la permission de supprimer des classes.');
        }

        $classe->delete();
        
        return redirect()->route('classes.index')->with('success', 'Classe supprimée avec succès');
    }
}

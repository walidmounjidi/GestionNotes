<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatiereController extends Controller
{
    public function index(Request $request)
    {
        $query = Matiere::query();
        
        if ($request->search) {
            $query->where('libelle', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
        }
        
        $matieres = $query->orderBy('libelle')->paginate(10);
        
        return view('matieres.index', compact('matieres'));
    }

    public function create()
    {
        return view('matieres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:matieres,code',
            'libelle' => 'required|string|max:255',
            'libelle_arabe' => 'nullable|string|max:255',
            'coefficient' => 'required|integer|min:1',
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Matiere::create($request->all());

        return redirect()->route('matieres.index')->with('success', 'Matière créée avec succès');
    }

    public function show(Matiere $matiere)
    {
        $matiere->load(['evaluations', 'classes']);
        return view('matieres.show', compact('matiere'));
    }

    public function edit(Matiere $matiere)
    {
        return view('matieres.edit', compact('matiere'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'code' => ['required', 'string', Rule::unique('matieres')->ignore($matiere->id)],
            'libelle' => 'required|string|max:255',
            'libelle_arabe' => 'nullable|string|max:255',
            'coefficient' => 'required|integer|min:1',
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $matiere->update($request->all());

        return redirect()->route('matieres.index')->with('success', 'Matière mise à jour avec succès');
    }

    public function destroy(Matiere $matiere)
    {
        $user = request()->user();
        if (!$user->hasRole(['admin'])) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas la permission de supprimer des matières.');
        }

        $matiere->delete();
        
        return redirect()->route('matieres.index')->with('success', 'Matière supprimée avec succès');
    }
}

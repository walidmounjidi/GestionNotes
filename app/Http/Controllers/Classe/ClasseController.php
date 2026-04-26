<?php

namespace App\Http\Controllers\Classe;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Specialization;
use App\Models\Level;
use App\Models\Utilisateur;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseController extends Controller
{
    public function index(Request $request)
    {
        $query = Classe::with(['specialization', 'level', 'etudiants']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('libelle', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }

        $classes = $query->withCount('etudiants')->paginate(10);
        $specializations = Specialization::all();

        return view('classes.index', compact('classes', 'specializations'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        $levels = Level::all();
        return view('classes.create', compact('specializations', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:classes,code',
            'libelle' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_scolaire' => 'required|integer',
            'description' => 'nullable|string',
            'specialization_id' => 'nullable|exists:specializations,id',
            'level_id' => 'nullable|exists:levels,id',
        ]);

        Classe::create($request->all());

        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès.');
    }

    public function show(Classe $classe)
    {
        $classe->load(['specialization', 'level', 'etudiants.utilisateur', 'teachers', 'matieres']);
        $assignedMatiereIds = $classe->matieres->pluck('id');
        $availableMatieres = Matiere::whereNotIn('id', $assignedMatiereIds)
                                        ->orderBy('libelle')
                                        ->get();
        return view('classes.show', compact('classe', 'availableMatieres'));
    }

    public function edit(Classe $classe)
    {
        $specializations = Specialization::all();
        $levels = Level::all();
        return view('classes.edit', compact('classe', 'specializations', 'levels'));
    }

    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'code' => 'required|string|unique:classes,code,' . $classe->id,
            'libelle' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_scolaire' => 'required|integer',
            'description' => 'nullable|string',
            'specialization_id' => 'nullable|exists:specializations,id',
            'level_id' => 'nullable|exists:levels,id',
        ]);

        $classe->update($request->all());

        return redirect()->route('classes.index')->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();

        return redirect()->route('classes.index')->with('success', 'Classe supprimée avec succès.');
    }

    public function assignMatiere(Request $request, Classe $classe)
    {
        $request->validate([
            'matiere_ids' => 'required|array',
            'matiere_ids.*' => 'exists:matieres,id',
        ]);

        $classe->matieres()->syncWithoutDetaching($request->matiere_ids);

        return redirect()->route('classes.show', $classe->id)->with('success', 'Matière(s) assignée(s) avec succès.');
    }

    public function removeMatiere(Request $request, Classe $classe, Matiere $matiere)
    {
        $classe->matieres()->detach($matiere->id);

        return redirect()->route('classes.show', $classe->id)->with('success', 'Matière retirée avec succès.');
    }
}

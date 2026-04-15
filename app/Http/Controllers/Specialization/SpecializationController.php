<?php

namespace App\Http\Controllers\Specialization;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::with(['levels', 'classes.etudiants'])->get();
        foreach ($specializations as $spec) {
            $spec->etudiants_count = $spec->etudiants()->count();
        }
        return view('specializations.index', compact('specializations'));
    }

    public function create()
    {
        return view('specializations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Specialization::create($request->all());

        return redirect()->route('specializations.index')->with('success', 'Spécialisation créée avec succès.');
    }

    public function show(Specialization $specialization)
    {
        $specialization->load(['levels', 'classes.etudiants']);
        $studentsCount = $specialization->classes->sum(function ($classe) {
            return $classe->etudiants->count();
        });

        return view('specializations.show', compact('specialization', 'studentsCount'));
    }

    public function edit(Specialization $specialization)
    {
        return view('specializations.edit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $specialization->update($request->all());

        return redirect()->route('specializations.index')->with('success', 'Spécialisation mise à jour avec succès.');
    }

    public function destroy(Specialization $specialization)
    {
        $specialization->delete();

        return redirect()->route('specializations.index')->with('success', 'Spécialisation supprimée avec succès.');
    }
}

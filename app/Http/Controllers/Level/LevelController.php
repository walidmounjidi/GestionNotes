<?php

namespace App\Http\Controllers\Level;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Specialization;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::with('specialization')->get();
        return view('levels.index', compact('levels'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        return view('levels.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'specialization_id' => 'required|exists:specializations,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        Level::create($request->all());

        return redirect()->route('levels.index')->with('success', 'Niveau créé avec succès.');
    }

    public function edit(Level $level)
    {
        $specializations = Specialization::all();
        return view('levels.edit', compact('level', 'specializations'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'specialization_id' => 'required|exists:specializations,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $level->update($request->all());

        return redirect()->route('levels.index')->with('success', 'Niveau mis à jour avec succès.');
    }

    public function destroy(Level $level)
    {
        $level->delete();

        return redirect()->route('levels.index')->with('success', 'Niveau supprimé avec succès.');
    }
}

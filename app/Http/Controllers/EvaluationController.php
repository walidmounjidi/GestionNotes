<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluation::with(['matiere', 'classe']);
        
        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }
        
        if ($request->classe_id) {
            $query->where('classe_id', $request->classe_id);
        }
        
        if ($request->matiere_id) {
            $query->where('matiere_id', $request->matiere_id);
        }
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        $evaluations = $query->orderBy('date_evaluation', 'desc')->paginate(10);
        $classes = Classe::all();
        $matieres = Matiere::all();
        
        return view('evaluations.index', compact('evaluations', 'classes', 'matieres'));
    }

    public function create()
    {
        $classes = Classe::all();
        $matieres = Matiere::all();
        return view('evaluations.create', compact('classes', 'matieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'date_evaluation' => 'required|date',
            'note_max' => 'required|numeric|min:0|max:100',
            'coefficient' => 'required|numeric|min:0.1|max:10',
            'session' => 'required|in:principal,rattrapage',
        ]);

        Evaluation::create($request->all());

        return redirect()->route('evaluations.index')->with('success', 'Évaluation créée avec succès');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['matiere', 'classe', 'notes.etudiant.utilisateur']);
        return view('evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        $classes = Classe::all();
        $matieres = Matiere::all();
        return view('evaluations.edit', compact('evaluation', 'classes', 'matieres'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'date_evaluation' => 'required|date',
            'note_max' => 'required|numeric|min:0|max:100',
            'coefficient' => 'required|numeric|min:0.1|max:10',
            'session' => 'required|in:principal,rattrapage',
        ]);

        $evaluation->update($request->all());

        return redirect()->route('evaluations.index')->with('success', 'Évaluation mise à jour avec succès');
    }

    public function destroy(Evaluation $evaluation)
    {
        $user = request()->user();
        if (!$user->hasRole(['admin'])) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas la permission de supprimer des évaluations.');
        }

        $evaluation->delete();
        
        return redirect()->route('evaluations.index')->with('success', 'Évaluation supprimée avec succès');
    }
}

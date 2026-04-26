<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\Utilisateur $user */
        $user = Auth::user();

        $query = Note::with(['etudiant.utilisateur', 'evaluation.matiere']);

        if ($user->isTeacher() && !$user->isAdmin()) {
            $query->whereHas('evaluation',
                fn($q) => $q->where('created_by', $user->id)
            );
        }

        if ($request->classe_id) {
            $query->whereHas('evaluation.classe', function ($q) use ($request) {
                $q->where('id', $request->classe_id);
            });
        }

        if ($request->matiere_id) {
            $query->whereHas('evaluation.matiere', function ($q) use ($request) {
                $q->where('id', $request->matiere_id);
            });
        }

        $notes = $query->orderBy('created_at', 'desc')->paginate(15);

        $statsQuery = Note::query();
        if ($user->isTeacher() && !$user->isAdmin()) {
            $statsQuery->whereHas('evaluation',
                fn($q) => $q->where('created_by', $user->id)
            );
        }

        $stats = [
            'total'      => (clone $statsQuery)->count(),
            'moyenne'    => (clone $statsQuery)->avg('note') ?? 0,
            'validees'   => (clone $statsQuery)->where('note', '>=', 10)->count(),
            'non_valides'=> (clone $statsQuery)->where('note', '<', 10)->count(),
        ];

        return view('notes.index', compact('notes', 'stats'));
    }

    public function create()
    {
        $evaluations = Evaluation::with(['matiere', 'classe'])->get();
        $etudiants = Etudiant::with('utilisateur')->get();
        return view('notes.create', compact('evaluations', 'etudiants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'evaluation_id' => 'required|exists:evaluations,id',
            'note' => 'required|numeric|min:0|max:20',
            'observation' => 'nullable|string',
        ]);

        $existingNote = Note::where('etudiant_id', $request->etudiant_id)
            ->where('evaluation_id', $request->evaluation_id)
            ->first();

        if ($existingNote) {
            return redirect()->back()->with('error', 'Une note existe déjà pour cet étudiant dans cette évaluation');
        }

        Note::create([
            'etudiant_id' => $request->etudiant_id,
            'evaluation_id' => $request->evaluation_id,
            'note' => $request->note,
            'observation' => $request->observation,
            'utilisateur_saisie_id' => Auth::id(),
        ]);

        return redirect()->route('notes.index')->with('success', 'Note enregistrée avec succès');
    }

    public function show(Note $note)
    {
        $note->load(['etudiant.utilisateur', 'evaluation.matiere', 'utilisateurSaisie']);
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        $evaluations = Evaluation::with(['matiere', 'classe'])->get();
        $etudiants = Etudiant::with('utilisateur')->get();
        return view('notes.edit', compact('note', 'evaluations', 'etudiants'));
    }

    public function update(Request $request, Note $note)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'evaluation_id' => 'required|exists:evaluations,id',
            'note' => 'required|numeric|min:0|max:20',
            'observation' => 'nullable|string',
        ]);

        $note->update([
            'note' => $request->note,
            'observation' => $request->observation,
        ]);

        return redirect()->route('notes.index')->with('success', 'Note mise à jour avec succès');
    }

    public function destroy(Note $note)
    {
        $user = request()->user();
        if (!$user->hasRole(['admin', 'teacher'])) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas la permission de supprimer des notes.');
        }

        $note->delete();
        
        return redirect()->route('notes.index')->with('success', 'Note supprimée avec succès');
    }

    // Saisir des notes pour une évaluation
    public function saisir(Evaluation $evaluation)
    {
        $evaluation->load(['matiere', 'classe']);
        
        $anneeScolaire = $evaluation->classe->annee_scolaire;
        
        $etudiantIds = \App\Models\Inscription::where('classe_id', $evaluation->classe_id)
            ->where('annee_scolaire', $anneeScolaire)
            ->where('statut', 'active')
            ->pluck('etudiant_id');
        
        $etudiants = \App\Models\Etudiant::with('utilisateur')
            ->whereIn('id', $etudiantIds)
            ->get();
        
        $notes = Note::where('evaluation_id', $evaluation->id)->get();
        
        return view('notes.saisir', compact('evaluation', 'etudiants', 'notes'));
    }

    // Enregistrer les notes pour une évaluation
    public function storeSaisir(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'notes' => 'required|array',
            'notes.*.etudiant_id' => 'required|exists:etudiants,id',
            'notes.*.note' => 'required|numeric|min:0|max:20',
        ]);

        if ($evaluation->isReadOnly()) {
            return redirect()->back()->with('error', 'Cette évaluation est en lecture seule. Les notes ne peuvent pas être modifiées.');
        }

        foreach ($request->notes as $noteData) {
            Note::updateOrCreate(
                [
                    'etudiant_id' => $noteData['etudiant_id'],
                    'evaluation_id' => $evaluation->id,
                ],
                [
                    'note' => $noteData['note'],
                    'observation' => $noteData['observation'] ?? null,
                    'utilisateur_saisie_id' => Auth::id(),
                ]
            );
        }

        return redirect()->route('evaluations.show', $evaluation)->with('success', 'Notes enregistrées avec succès');
    }
}

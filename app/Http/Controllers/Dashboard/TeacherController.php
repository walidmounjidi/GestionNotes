<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $assignedClasses = $user->classes()->with(['level', 'specialization', 'etudiants.utilisateur'])->get();
        $assignedMatieres = $user->matiereClasses()->with(['matiere', 'classe.level', 'classe.specialization'])->get();

        $stats = [
            'classes_count' => $assignedClasses->count(),
            'matieres_count' => $assignedMatieres->count(),
            'students_count' => $assignedClasses->sum(function ($classe) {
                return $classe->etudiants->count();
            }),
        ];

        $studentsByClass = $assignedClasses->map(function ($classe) {
            return [
                'classe' => $classe,
                'students' => $classe->etudiants->map(function ($etudiant) {
                    return [
                        'etudiant' => $etudiant,
                        'notes' => $etudiant->notes()->with('evaluation.matiere')->get(),
                        'moyenne' => $etudiant->notes->avg('note'),
                    ];
                }),
            ];
        });

        $availableEvaluations = Evaluation::with(['matiere', 'classe'])
            ->where(function ($query) use ($user, $assignedClasses) {
                $query->whereHas('classe.teachers', function ($q) use ($user) {
                    $q->where('utilisateur_id', $user->id);
                })
                    ->orWhereIn('classe_id', $assignedClasses->pluck('id'));
            })
            ->where('date_evaluation', '<=', now())
            ->orderBy('date_evaluation', 'desc')
            ->get();

        $upcomingEvaluations = Evaluation::with(['matiere', 'classe'])
            ->where(function ($query) use ($user, $assignedClasses) {
                $query->whereHas('classe.teachers', function ($q) use ($user) {
                    $q->where('utilisateur_id', $user->id);
                })
                    ->orWhereIn('classe_id', $assignedClasses->pluck('id'));
            })
            ->where('date_evaluation', '>', now())
            ->orderBy('date_evaluation', 'asc')
            ->take(5)
            ->get();

        $recentNotes = Note::whereHas('evaluation', function ($query) use ($user, $assignedClasses) {
            $query->whereHas('classe.teachers', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id);
            })
                ->orWhereIn('classe_id', $assignedClasses->pluck('id'));
        })
            ->with(['evaluation.matiere', 'evaluation.classe', 'etudiant.utilisateur'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.teacher', compact(
            'stats',
            'assignedClasses',
            'assignedMatieres',
            'studentsByClass',
            'availableEvaluations',
            'upcomingEvaluations',
            'recentNotes'
        ));
    }

    public function updateGrades(Request $request)
    {
        $request->validate([
            'notes' => 'required|array',
            'notes.*.etudiant_id' => 'required|exists:etudiants,id',
            'notes.*.evaluation_id' => 'required|exists:evaluations,id',
            'notes.*.note' => 'required|numeric|min:0|max:20',
        ]);

        $user = Auth::user();

        foreach ($request->notes as $noteData) {
            $evaluation = Evaluation::findOrFail($noteData['evaluation_id']);

            if (! $evaluation->classe->teachers()->where('utilisateur_id', $user->id)->exists()) {
                continue;
            }

            Note::updateOrCreate(
                [
                    'etudiant_id' => $noteData['etudiant_id'],
                    'evaluation_id' => $noteData['evaluation_id'],
                ],
                [
                    'note' => $noteData['note'],
                    'valide' => true,
                ]
            );
        }

        return back()->with('success', 'Notes mises à jour avec succès.');
    }
}

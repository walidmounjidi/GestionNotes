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

        $evaluationsForGradeEntry = Evaluation::with(['matiere', 'classe'])
            ->where(function ($query) use ($user, $assignedClasses) {
                $query->whereHas('classe.teachers', function ($q) use ($user) {
                    $q->where('utilisateur_id', $user->id);
                })
                    ->orWhereIn('classe_id', $assignedClasses->pluck('id'));
            })
            ->where('date_evaluation', '<=', now())
            ->orderBy('date_evaluation', 'desc')
            ->get()
            ->groupBy(fn ($e) => $e->classe->libelle);

        $stats = [
            'classes_count' => $assignedClasses->count(),
            'matieres_count' => $assignedMatieres->count(),
            'students_count' => $assignedClasses->sum(function ($classe) {
                return $classe->etudiants->count();
            }),
            'total_evaluations' => Evaluation::whereHas('classe', function ($q) use ($user) {
                $q->whereHas('teachers', function ($q2) use ($user) {
                    $q2->where('utilisateur_id', $user->id);
                });
            })->count(),
            'total_notes' => Note::whereHas('evaluation.classe.teachers', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id);
            })->count(),
            'pending_validation' => Note::whereHas('evaluation.classe.teachers', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id);
            })->where('valide', false)->count(),
            'validated' => Note::whereHas('evaluation.classe.teachers', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id);
            })->where('valide', true)->count(),
        ];

        $moyenne_generale = Note::whereHas('evaluation.classe.teachers', function ($q) use ($user) {
            $q->where('utilisateur_id', $user->id);
        })->avg('note') ?? 0;

        $recent_evaluations = Evaluation::with(['matiere', 'classe'])
            ->whereHas('classe.teachers', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id);
            })
            ->orderBy('date_evaluation', 'desc')
            ->take(5)
            ->get();

        $unvalidated_notes = Note::with(['etudiant.utilisateur', 'evaluation.matiere', 'evaluation.classe'])
            ->whereHas('evaluation.classe.teachers', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id);
            })
            ->where('valide', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

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

        return view('dashboard.teacher', compact(
            'stats',
            'assignedClasses',
            'assignedMatieres',
            'recent_evaluations',
            'unvalidated_notes',
            'studentsByClass',
            'availableEvaluations',
            'moyenne_generale'
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

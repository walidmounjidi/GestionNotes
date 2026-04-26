<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Classe;
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
            'matieres_count' => $user->subjects()->count(),
            'students_count' => $assignedClasses->sum(function ($classe) {
                return $classe->etudiants->count();
            }),
        ];

        $mySubjects = $user->subjects()->orderBy('libelle')->get();

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

        $evaluationsCalendar = $this->getEvaluationsCalendar($user, $assignedClasses);

        return view('dashboard.teacher', compact(
            'stats',
            'assignedClasses',
            'assignedMatieres',
            'mySubjects',
            'availableEvaluations',
            'upcomingEvaluations',
            'recentNotes',
            'evaluationsCalendar'
        ));
    }

    private function getEvaluationsCalendar($user, $assignedClasses)
    {
        $startDate = now()->startOfMonth()->subWeek();
        $endDate = now()->endOfMonth()->addWeek();

        return Evaluation::with(['matiere', 'classe'])
            ->where(function ($query) use ($user, $assignedClasses) {
                $query->whereHas('classe.teachers', function ($q) use ($user) {
                    $q->where('utilisateur_id', $user->id);
                })
                    ->orWhereIn('classe_id', $assignedClasses->pluck('id'));
            })
            ->whereBetween('date_evaluation', [$startDate, $endDate])
            ->orderBy('date_evaluation')
            ->get()
            ->groupBy(fn($e) => $e->date_evaluation->format('Y-m-d'));
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

    public function classeDetail(Classe $classe)
    {
        $user = Auth::user();

        if (!$classe->teachers()->where('utilisateur_id', $user->id)->exists()) {
            abort(403, 'Vous n\'êtes pas assigné à cette classe.');
        }

        $classe->load(['etudiants.utilisateur', 'matieres']);

        $teacherSubjectIds = $user->subjects()->pluck('matiere_id');
        $classMatiereIds = $classe->matieres()->pluck('matieres.id');
        $relevantMatieres = \App\Models\Matiere::whereIn('id',
            $teacherSubjectIds->intersect($classMatiereIds)
        )->get();

        if ($relevantMatieres->isEmpty()) {
            $relevantMatieres = $user->subjects()->get();
        }

        $evalTypes = ['test_1', 'test_2', 'test_3', 'examen_final'];

        $evaluationsByMatiere = [];
        foreach ($relevantMatieres as $matiere) {
            $evals = [];
            foreach ($evalTypes as $type) {
                $evals[$type] = Evaluation::where('classe_id', $classe->id)
                    ->where('matiere_id', $matiere->id)
                    ->where('type', $type)
                    ->first();
            }
            $evaluationsByMatiere[$matiere->id] = [
                'matiere' => $matiere,
                'evaluations' => $evals,
            ];
        }

        $allEvalIds = collect($evaluationsByMatiere)
            ->flatMap(fn($m) => collect($m['evaluations'])->filter()->pluck('id'))
            ->values();

        $notesByEtudiant = Note::whereIn('evaluation_id', $allEvalIds)
            ->get()
            ->groupBy('etudiant_id');

        $etudiants = $classe->etudiants->sortBy(fn($e) => $e->utilisateur->nom ?? '');

        return view('dashboard.teacher-classe', compact(
            'classe',
            'etudiants',
            'evaluationsByMatiere',
            'notesByEtudiant',
            'evalTypes'
        ));
    }

    public function saveEvaluation(Request $request, Classe $classe)
    {
        $user = Auth::user();

        if (!$classe->teachers()->where('utilisateur_id', $user->id)->exists()) {
            abort(403);
        }

        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'type' => 'required|in:test_1,test_2,test_3',
            'date_evaluation' => 'required|date',
        ]);

        Evaluation::updateOrCreate(
            [
                'classe_id' => $classe->id,
                'matiere_id' => $request->matiere_id,
                'type' => $request->type,
            ],
            [
                'description' => match($request->type) {
                    'test_1' => 'Test 1',
                    'test_2' => 'Test 2',
                    'test_3' => 'Test 3',
                },
                'date_evaluation' => $request->date_evaluation,
                'note_max' => 20,
                'coefficient' => 1,
                'session' => 'principal',
            ]
        );

        return redirect()
            ->route('teacher.classe.detail', $classe->id)
            ->with('success', 'Date de l\'évaluation enregistrée.');
    }

    public function saveNotes(Request $request, Classe $classe)
    {
        $user = Auth::user();

        if (!$classe->teachers()->where('utilisateur_id', $user->id)->exists()) {
            abort(403);
        }

        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'notes' => 'required|array',
            'notes.*.etudiant_id' => 'required|exists:etudiants,id',
            'notes.*.note' => 'nullable|numeric|min:0|max:20',
        ]);

        $evaluation = Evaluation::where('id', $request->evaluation_id)
            ->where('classe_id', $classe->id)
            ->firstOrFail();

        foreach ($request->notes as $noteData) {
            if ($noteData['note'] === null || $noteData['note'] === '') {
                continue;
            }
            Note::updateOrCreate(
                [
                    'etudiant_id' => $noteData['etudiant_id'],
                    'evaluation_id' => $evaluation->id,
                ],
                [
                    'note' => $noteData['note'],
                    'utilisateur_saisie_id' => Auth::id(),
                ]
            );
        }

        return redirect()
            ->route('teacher.classe.detail', $classe->id)
            ->with('success', 'Notes enregistrées avec succès.');
    }
}

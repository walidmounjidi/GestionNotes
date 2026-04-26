<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (! $etudiant) {
            return view('dashboard.student', [
                'etudiant' => null,
                'myNotes' => collect(),
                'mySubjects' => collect(),
                'moyenne_generale' => null,
                'progressIndicator' => null,
                'upcomingEvaluations' => collect(),
                'gradeTable' => collect(),
                'error' => 'Profil étudiant non trouvé. Veuillez contacter l\'administrateur.',
            ]);
        }

        $etudiant->load('classe.matieres');

        $myNotes = Note::with(['evaluation.matiere', 'evaluation.classe'])
            ->where('etudiant_id', $etudiant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $moyenne_generale = $myNotes->avg('note');

        $mySubjects = [];
        if ($etudiant->classe_id && $etudiant->classe) {
            $subjectNotes = Note::with('evaluation')
                ->where('etudiant_id', $etudiant->id)
                ->whereHas('evaluation', fn($q) => $q->whereIn('matiere_id', $etudiant->classe->matieres->pluck('id')))
                ->get()
                ->groupBy(fn($note) => $note->evaluation->matiere_id);

            $mySubjects = $etudiant->classe->matieres->map(function ($matiere) use ($subjectNotes) {
                $notes = $subjectNotes->get($matiere->id, collect());
                return [
                    'matiere' => $matiere,
                    'average' => $notes->isNotEmpty() ? round($notes->avg('note'), 2) : null,
                    'notes_count' => $notes->count(),
                ];
            });
        }

        $progressIndicator = null;
        if ($moyenne_generale !== null) {
            if ($moyenne_generale >= 16) {
                $progressIndicator = ['label' => 'Excellent', 'color' => 'emerald'];
            } elseif ($moyenne_generale >= 14) {
                $progressIndicator = ['label' => 'Bien', 'color' => 'blue'];
            } elseif ($moyenne_generale >= 10) {
                $progressIndicator = ['label' => 'Passable', 'color' => 'amber'];
            } else {
                $progressIndicator = ['label' => 'Insuffisant', 'color' => 'red'];
            }
        }

        $upcomingEvaluations = collect();
        if ($etudiant->classe_id) {
            $upcomingEvaluations = Evaluation::with(['matiere', 'classe'])
                ->where('classe_id', $etudiant->classe_id)
                ->where('date_evaluation', '>', now())
                ->orderBy('date_evaluation', 'asc')
                ->take(5)
                ->get();
        }

        $evalTypes = ['test_1', 'test_2', 'test_3', 'examen_final'];
        $gradeTable = [];

        if ($etudiant->classe_id && $etudiant->classe) {
            foreach ($etudiant->classe->matieres as $matiere) {
                $row = ['matiere' => $matiere, 'notes' => [], 'moyenne' => null];
                $notesForAvg = [];

                foreach ($evalTypes as $type) {
                    $evaluation = Evaluation::where('matiere_id', $matiere->id)
                        ->where('classe_id', $etudiant->classe_id)
                        ->where('type', $type)
                        ->first();

                    if ($evaluation) {
                        $note = $myNotes->where('evaluation_id', $evaluation->id)->first();
                        $row['notes'][$type] = $note ? $note->note : null;
                        if ($note) {
                            $notesForAvg[] = $note->note;
                        }
                    } else {
                        $row['notes'][$type] = null;
                    }
                }

                $row['moyenne'] = count($notesForAvg) > 0
                    ? round(array_sum($notesForAvg) / count($notesForAvg), 2)
                    : null;

                $gradeTable[] = $row;
            }
        }

        return view('dashboard.student', compact(
            'etudiant',
            'myNotes',
            'mySubjects',
            'moyenne_generale',
            'progressIndicator',
            'upcomingEvaluations',
            'gradeTable'
        ));
    }

    public function bulletin()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (! $etudiant) {
            return view('dashboard.bulletin', [
                'etudiant' => null,
                'bulletinData' => collect(),
                'moyenne_generale' => null,
                'classRanking' => null,
            ]);
        }

        $etudiant->load('classe.matieres');
        $classe = $etudiant->classe;

        $matieres = $classe ? $classe->matieres : collect();

        $bulletinData = $matieres->map(function ($matiere) use ($etudiant) {
            $notes = Note::where('etudiant_id', $etudiant->id)
                ->whereHas('evaluation', fn($q) => $q->where('matiere_id', $matiere->id))
                ->with('evaluation')
                ->get();

            $sumWeightedNotes = 0;
            $sumCoefficients = 0;
            $totalNotes = $notes->count();

            foreach ($notes as $note) {
                $coefficient = $note->evaluation->coefficient ?? 1;
                $sumWeightedNotes += $note->note * $coefficient;
                $sumCoefficients += $coefficient;
            }

            $average = $sumCoefficients > 0 ? round($sumWeightedNotes / $sumCoefficients, 2) : null;

            return [
                'matiere' => $matiere,
                'average' => $average,
                'total_notes' => $totalNotes,
                'coefficient' => $matiere->coefficient ?? 1,
            ];
        });

        $moyenne_generale = $bulletinData->whereNotNull('average')->count() > 0
            ? round($bulletinData->whereNotNull('average')->avg('average'), 2)
            : null;

        $classRanking = null;
        if ($classe && $moyenne_generale !== null) {
            $classStudents = $classe->etudiants()->with('utilisateur')->get();
            $rankedStudents = $classStudents->map(function ($student) {
                $notes = Note::where('etudiant_id', $student->id)->get();
                return [
                    'etudiant' => $student,
                    'average' => $notes->avg('note') ?? 0,
                ];
            })->sortByDesc('average')->values();

            $studentRank = $rankedStudents->search(fn($item) => $item['etudiant']->id === $etudiant->id);
            $classRanking = [
                'rank' => $studentRank !== false ? $studentRank + 1 : null,
                'total' => $rankedStudents->count(),
            ];
        }

        return view('dashboard.bulletin', compact(
            'etudiant',
            'bulletinData',
            'moyenne_generale',
            'classRanking'
        ));
    }

    public function schedule()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (! $etudiant) {
            return view('dashboard.schedule', [
                'etudiant' => null,
                'scheduleData' => collect(),
            ]);
        }

        $etudiant->load('classe.matieres.evaluations');
        $classe = $etudiant->classe;

        $evaluations = collect();
        if ($classe) {
            $evaluations = Evaluation::where('classe_id', $classe->id)
                ->where('date_evaluation', '>', now())
                ->with('matiere')
                ->orderBy('date_evaluation')
                ->get()
                ->groupBy(fn($e) => $e->date_evaluation->format('Y-m-d'));
        }

        return view('dashboard.schedule', [
            'etudiant' => $etudiant,
            'scheduleData' => $evaluations
        ]);
    }
}
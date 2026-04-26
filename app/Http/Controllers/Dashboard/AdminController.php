<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Utilisateur;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers' => Utilisateur::whereHas('roles', function ($q) {
                $q->where('code', 'teacher');
            })->count(),
            'total_students' => Etudiant::count(),
            'total_classes' => Classe::count(),
        ];

        $stats['success_rate_global'] = $this->calculateGlobalSuccessRate();
        $stats['success_rates_by_class'] = $this->calculateSuccessRateByClass();
        $stats['success_rates_by_subject'] = $this->calculateSuccessRateBySubject();

        // Get class capacity information
        $classes = Classe::with(['level', 'specialization'])
            ->orderBy('libelle')
            ->get()
            ->map(function ($classe) {
                return [
                    'id' => $classe->id,
                    'libelle' => $classe->libelle,
                    'level' => $classe->level->libelle ?? '',
                    'specialization' => $classe->specialization->libelle ?? '',
                    'student_count' => $classe->student_count,
                    'max_students' => $classe->max_students,
                    'available_slots' => $classe->availableSlots(),
                    'is_full' => $classe->isFull(),
                    'capacity_percentage' => $classe->max_students > 0 
                        ? round(($classe->student_count / $classe->max_students) * 100, 1)
                        : 0,
                ];
            });

        return view('dashboard.admin', compact('stats', 'classes'));
    }

    private function calculateGlobalSuccessRate(): float
    {
        $totalNotes = Note::whereHas('evaluation', fn($q) => $q->where('note_max', 20))->count();
        if ($totalNotes === 0) {
            return 0;
        }
        $successNotes = Note::whereHas('evaluation', fn($q) => $q->where('note_max', 20))
            ->where('note', '>=', 10)
            ->count();
        return round(($successNotes / $totalNotes) * 100, 1);
    }

    private function calculateSuccessRateByClass(): array
    {
        return Classe::with('etudiants')->get()->map(function ($classe) {
            $notes = Note::whereHas('evaluation', fn($q) => $q->where('classe_id', $classe->id))->get();
            $total = $notes->count();
            $success = $notes->where('note', '>=', 10)->count();
            return [
                'classe_id' => $classe->id,
                'classe_name' => $classe->libelle,
                'total_notes' => $total,
                'success_rate' => $total > 0 ? round(($success / $total) * 100, 1) : 0,
            ];
        })->filter(fn($item) => $item['total_notes'] > 0)->values()->toArray();
    }

    private function calculateSuccessRateBySubject(): array
    {
        return \App\Models\Matiere::with('evaluations')->get()->map(function ($matiere) {
            $notes = Note::whereHas('evaluation', fn($q) => $q->where('matiere_id', $matiere->id))->get();
            $total = $notes->count();
            $success = $notes->where('note', '>=', 10)->count();
            return [
                'matiere_id' => $matiere->id,
                'matiere_name' => $matiere->libelle,
                'total_notes' => $total,
                'success_rate' => $total > 0 ? round(($success / $total) * 100, 1) : 0,
            ];
        })->filter(fn($item) => $item['total_notes'] > 0)->values()->toArray();
    }

    public function assignSubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:utilisateurs,id',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $teacher = Utilisateur::findOrFail($request->teacher_id);
        $matiere = Matiere::findOrFail($request->matiere_id);
        $classe = Classe::findOrFail($request->classe_id);

        // Check if teacher is already assigned to this subject for this class
        $existing = $teacher->matiereClasses()
            ->where('matiere_id', $matiere->id)
            ->where('classe_id', $classe->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Le professeur est déjà assigné à cette matière pour cette classe.');
        }

        // Create the assignment
        \App\Models\TeacherMatiere::create([
            'utilisateur_id' => $teacher->id,
            'matiere_id' => $matiere->id,
            'classe_id' => $classe->id,
        ]);

        return redirect()->back()->with('success', 'Assignation de matière réussie.');
    }

    public function removeSubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:utilisateurs,id',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $teacher = Utilisateur::findOrFail($request->teacher_id);
        $teacher->matiereClasses()
            ->where('matiere_id', $request->matiere_id)
            ->where('classe_id', $request->classe_id)
            ->detach();

        return redirect()->back()->with('success', 'Assignation de matière supprimée.');
    }

    public function saveFinalExam(Request $request, Classe $classe)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'date_evaluation' => 'required|date',
        ]);

        Evaluation::updateOrCreate(
            [
                'classe_id' => $classe->id,
                'matiere_id' => $request->matiere_id,
                'type' => 'examen_final',
            ],
            [
                'description' => 'Examen Final',
                'date_evaluation' => $request->date_evaluation,
                'note_max' => 20,
                'coefficient' => 2,
                'session' => 'principal',
            ]
        );

        return redirect()->back()->with('success', 'Date de l\'examen final enregistrée.');
    }
}

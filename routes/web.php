<?php

use App\Http\Controllers\Level\LevelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Specialization\SpecializationController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, '__invoke'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [\App\Http\Controllers\Dashboard\AdminController::class, 'index'])->name('dashboard.admin');
        Route::resource('utilisateurs', UtilisateurController::class);
        Route::resource('teacher', TeacherController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('etudiants', \App\Http\Controllers\EtudiantController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('matieres', \App\Http\Controllers\MatiereController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('specializations', SpecializationController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('levels', LevelController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('classes', \App\Http\Controllers\ClasseController::class)->parameters(['classes' => 'classe'])->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::post('/admin/assignSubject', [\App\Http\Controllers\Dashboard\AdminController::class, 'assignSubject'])->name('admin.assignSubject');
        Route::post('/admin/remove-subject', [\App\Http\Controllers\Dashboard\AdminController::class, 'removeSubject'])->name('admin.removeSubject');
    });

    Route::middleware('role:admin,teacher')->group(function () {
        Route::resource('evaluations', \App\Http\Controllers\EvaluationController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'saisir'])->name('notes.saisir');
        Route::post('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'storeSaisir'])->name('notes.storeSaisir');
        Route::resource('notes', \App\Http\Controllers\NoteController::class)->only(['index', 'show', 'edit', 'update']);
    });

    Route::middleware('role:teacher')->group(function () {
        Route::get('/dashboard/teacher', [\App\Http\Controllers\Dashboard\TeacherController::class, 'index'])->name('dashboard.teacher');
        Route::post('/teacher/update-grades', [\App\Http\Controllers\Dashboard\TeacherController::class, 'updateGrades'])->name('teacher.updateGrades');
    });

    Route::middleware('role:student')->group(function () {
        Route::get('/dashboard/student', [\App\Http\Controllers\Dashboard\StudentController::class, 'index'])->name('dashboard.student');
    });
});

require __DIR__.'/auth.php';
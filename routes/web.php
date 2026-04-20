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

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [\App\Http\Controllers\Dashboard\AdminController::class, 'index'])->name('dashboard.admin');
        Route::resource('utilisateurs', UtilisateurController::class);
        Route::resource('teacher', TeacherController::class);
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/dashboard/manager', [\App\Http\Controllers\Dashboard\ManagerController::class, 'index'])->name('dashboard.manager');
        Route::post('/manager/assign-subject', [\App\Http\Controllers\Dashboard\ManagerController::class, 'assignSubject'])->name('manager.assignSubject');
        Route::post('/manager/remove-subject', [\App\Http\Controllers\Dashboard\ManagerController::class, 'removeSubject'])->name('manager.removeSubject');
        Route::resource('etudiants', \App\Http\Controllers\EtudiantController::class);
    });

    Route::middleware('role:teacher')->group(function () {
        Route::get('/dashboard/teacher', [\App\Http\Controllers\Dashboard\TeacherController::class, 'index'])->name('dashboard.teacher');
        Route::post('/teacher/update-grades', [\App\Http\Controllers\Dashboard\TeacherController::class, 'updateGrades'])->name('teacher.updateGrades');
    });

    Route::middleware('role:student')->group(function () {
        Route::get('/dashboard/student', [\App\Http\Controllers\Dashboard\StudentController::class, 'index'])->name('dashboard.student');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('matieres', \App\Http\Controllers\MatiereController::class);
        Route::resource('specializations', SpecializationController::class);
        Route::resource('levels', LevelController::class);
        Route::resource('classes', \App\Http\Controllers\ClasseController::class)->parameters(['classes' => 'classe'])->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    });

    Route::middleware('role:admin,manager,teacher')->group(function () {
        Route::resource('evaluations', \App\Http\Controllers\EvaluationController::class);
        Route::get('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'saisir'])->name('notes.saisir');
        Route::post('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'storeSaisir'])->name('notes.storeSaisir');
    });

    Route::middleware('role:admin,manager,teacher')->group(function () {
        Route::resource('notes', \App\Http\Controllers\NoteController::class)->except(['saisir']);
    });
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\Specialization\SpecializationController;
use App\Http\Controllers\Level\LevelController;
use App\Http\Controllers\Classe\ClasseController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, '__invoke'])->name('dashboard');
    Route::get('/dashboard/admin', [\App\Http\Controllers\Dashboard\AdminController::class, 'index'])->middleware('role:admin')->name('dashboard.admin');
    Route::get('/dashboard/manager', [\App\Http\Controllers\Dashboard\ManagerController::class, 'index'])->middleware('role:manager')->name('dashboard.manager');
    Route::get('/dashboard/teacher', [\App\Http\Controllers\Dashboard\TeacherController::class, 'index'])->middleware('role:teacher')->name('dashboard.teacher');
    Route::get('/dashboard/student', [\App\Http\Controllers\Dashboard\StudentController::class, 'index'])->middleware('role:student')->name('dashboard.student');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('etudiants', \App\Http\Controllers\EtudiantController::class);
        Route::resource('matieres', \App\Http\Controllers\MatiereController::class);
        Route::resource('specializations', SpecializationController::class);
        Route::resource('levels', LevelController::class);
        Route::resource('classes', \App\Http\Controllers\ClasseController::class)->parameters(['classes' => 'classe'])->only(['create', 'edit', 'store', 'update', 'destroy']);
        
        Route::post('/manager/assign-subject', [\App\Http\Controllers\Dashboard\ManagerController::class, 'assignSubject'])->name('manager.assignSubject');
        Route::post('/manager/remove-subject', [\App\Http\Controllers\Dashboard\ManagerController::class, 'removeSubject'])->name('manager.removeSubject');
    });

    Route::middleware('role:admin,manager,teacher')->group(function () {
        Route::resource('classes', \App\Http\Controllers\ClasseController::class)->parameters(['classes' => 'classe'])->only(['index', 'show']);
        Route::resource('evaluations', \App\Http\Controllers\EvaluationController::class);
    });

    Route::middleware('role:admin,manager,teacher')->group(function () {
        Route::get('/teacher/dashboard', [\App\Http\Controllers\Teacher\TeacherController::class, 'dashboard'])->name('teacher.dashboard');
        Route::resource('teacher', TeacherController::class)->names([
            'index' => 'teacher.index',
            'create' => 'teacher.create',
            'store' => 'teacher.store',
            'show' => 'teacher.show',
            'edit' => 'teacher.edit',
            'update' => 'teacher.update',
            'destroy' => 'teacher.destroy',
        ]);
    });

    Route::middleware('role:admin,manager,teacher')->group(function () {
        Route::resource('notes', \App\Http\Controllers\NoteController::class)->except(['saisir']);
        Route::get('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'saisir'])->name('notes.saisir');
        Route::post('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'storeSaisir'])->name('notes.storeSaisir');
        
        Route::post('/teacher/update-grades', [\App\Http\Controllers\Dashboard\TeacherController::class, 'updateGrades'])->name('teacher.updateGrades');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('utilisateurs', UtilisateurController::class);
    });

    Route::middleware('role:student')->group(function () {
        Route::get('/student/dashboard', [\App\Http\Controllers\Student\StudentController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/student/grades', [\App\Http\Controllers\Student\StudentController::class, 'myGrades'])->name('student.grades');
        Route::get('/student/classmates', [\App\Http\Controllers\Student\StudentController::class, 'classmates'])->name('student.classmates');
    });
});

require __DIR__.'/auth.php';

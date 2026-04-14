<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, '__invoke'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Students routes
    Route::resource('etudiants', \App\Http\Controllers\EtudiantController::class);

    // Subjects routes
    Route::resource('matieres', \App\Http\Controllers\MatiereController::class);

    // Classes routes
    Route::resource('classes', \App\Http\Controllers\ClasseController::class);

    // Evaluations routes
    Route::resource('evaluations', \App\Http\Controllers\EvaluationController::class);

    // Notes routes
    Route::resource('notes', \App\Http\Controllers\NoteController::class)->except(['saisir']);
    Route::get('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'saisir'])->name('notes.saisir');
    Route::post('evaluations/{evaluation}/saisir', [\App\Http\Controllers\NoteController::class, 'storeSaisir'])->name('notes.storeSaisir');
});

require __DIR__.'/auth.php';

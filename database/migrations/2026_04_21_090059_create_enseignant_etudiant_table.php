<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enseignant_etudiant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('annee_universitaire');
            $table->string('specialisation');
            $table->timestamps();
            
            $table->unique(['etudiant_id', 'enseignant_id', 'annee_universitaire'], 'enseignant_etudiant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignant_etudiant');
    }
};

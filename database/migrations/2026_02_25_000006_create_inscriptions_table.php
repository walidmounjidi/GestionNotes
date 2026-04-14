<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->integer('annee_scolaire');
            $table->enum('statut', ['active', 'suspendue', 'terminee'])->default('active');
            $table->date('date_inscription');
            $table->text('observation')->nullable();
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['etudiant_id', 'classe_id', 'annee_scolaire']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->decimal('note', 5, 2);
            $table->text('observation')->nullable();
            $table->timestamp('date_saisie')->useCurrent();
            $table->foreignId('utilisateur_saisie_id')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate grades for same student and evaluation
            $table->unique(['etudiant_id', 'evaluation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};

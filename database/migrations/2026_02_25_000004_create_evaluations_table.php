<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->string('type'); // examen, devoir, projet, etc.
            $table->string('description');
            $table->date('date_evaluation');
            $table->decimal('note_max', 5, 2)->default(20);
            $table->decimal('coefficient', 3, 2)->default(1);
            $table->enum('session', ['principal', 'rattrapage'])->default('principal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_classe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['utilisateur_id', 'classe_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_classe');
    }
};

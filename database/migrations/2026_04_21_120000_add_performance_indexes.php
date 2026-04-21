<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->index('etudiant_id');
            $table->index('evaluation_id');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->index('classe_id');
            $table->index('matiere_id');
            $table->index('date_evaluation');
        });

        Schema::table('etudiants', function (Blueprint $table) {
            $table->index('classe_id');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['etudiant_id']);
            $table->dropIndex(['evaluation_id']);
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropIndex(['classe_id']);
            $table->dropIndex(['matiere_id']);
            $table->dropIndex(['date_evaluation']);
        });

        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropIndex(['classe_id']);
        });
    }
};
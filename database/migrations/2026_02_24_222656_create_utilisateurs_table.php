<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('password'); // هاد هو المهم (بدل mot_de_passe)
            $table->enum('etat', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->timestamp('derniere_connexion_at')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamp('email_verified_at')->nullable(); // زيد هاد (مهم لـ Breeze)
            $table->rememberToken(); // زيد هاد (مهم لـ Breeze)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
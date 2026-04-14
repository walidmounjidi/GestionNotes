<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UtilisateurSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        Utilisateur::create([
            'uuid' => Str::uuid()->toString(),
            'nom' => 'Admin',
            'prenom' => 'Système',
            'email' => 'admin@etu-note.ma',
            'password' => Hash::make('password'),
            'telephone' => '+212 6 00 00 00 00',
            'etat' => 'actif',
            'email_verified_at' => now(),
        ]);

        // Create test user
        Utilisateur::create([
            'uuid' => Str::uuid()->toString(),
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'telephone' => '+212 6 12 34 56 78',
            'etat' => 'actif',
            'email_verified_at' => now(),
        ]);
    }
}

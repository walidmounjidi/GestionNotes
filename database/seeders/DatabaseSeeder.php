<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Inscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (Utilisateur::where('email', 'admin@etu-note.ma')->exists()) {
            $this->command->info('Admin user already exists, skipping...');
            return;
        }

        // Create admin user
        $admin = Utilisateur::create([
            'uuid' => Str::uuid()->toString(),
            'nom' => 'Admin',
            'prenom' => 'Système',
            'email' => 'admin@etu-note.ma',
            'password' => Hash::make('password'),
            'telephone' => '+212 6 00 00 00 00',
            'etat' => 'actif',
            'email_verified_at' => now(),
        ]);

        // Create classes
        $classes = [
            ['code' => 'L1INFO', 'libelle' => ' Licence 1 Info', 'niveau' => 'L1', 'annee_scolaire' => 2025],
            ['code' => 'L2INFO', 'libelle' => ' Licence 2 Info', 'niveau' => 'L2', 'annee_scolaire' => 2025],
            ['code' => 'L3INFO', 'libelle' => ' Licence 3 Info', 'niveau' => 'L3', 'annee_scolaire' => 2025],
            ['code' => 'M1INFO', 'libelle' => ' Master 1 Info', 'niveau' => 'M1', 'annee_scolaire' => 2025],
            ['code' => 'M2INFO', 'libelle' => ' Master 2 Info', 'niveau' => 'M2', 'annee_scolaire' => 2025],
        ];

        foreach ($classes as $classe) {
            Classe::create($classe);
        }

        // Create subjects
        $matieres = [
            ['code' => 'ALGO', 'libelle' => 'Algorithmique', 'coefficient' => 3, 'credits' => 6],
            ['code' => 'BD', 'libelle' => 'Base de Données', 'coefficient' => 3, 'credits' => 5],
            ['code' => 'WEB', 'libelle' => 'Développement Web', 'coefficient' => 2, 'credits' => 4],
            ['code' => 'RES', 'libelle' => 'Réseaux', 'coefficient' => 2, 'credits' => 4],
            ['code' => 'MATH', 'libelle' => 'Mathématiques', 'coefficient' => 4, 'credits' => 6],
            ['code' => 'ANG', 'libelle' => 'Anglais', 'coefficient' => 1, 'credits' => 2],
        ];

        foreach ($matieres as $matiere) {
            Matiere::create($matiere);
        }

        // Create some students
        $students = [
            ['nom' => 'Alami', 'prenom' => 'Youssef', 'email' => 'alami@etu-note.ma', 'date_naissance' => '2004-05-15', 'lieu_naissance' => 'Rabat', 'sexe' => 'M'],
            ['nom' => 'Benali', 'prenom' => 'Fatima', 'email' => 'benali@etu-note.ma', 'date_naissance' => '2004-03-22', 'lieu_naissance' => 'Casablanca', 'sexe' => 'F'],
            ['nom' => 'Haddad', 'prenom' => 'Ahmed', 'email' => 'haddad@etu-note.ma', 'date_naissance' => '2004-08-10', 'lieu_naissance' => 'Marrakech', 'sexe' => 'M'],
            ['nom' => 'Bennis', 'prenom' => 'Aicha', 'email' => 'bennis@etu-note.ma', 'date_naissance' => '2004-11-30', 'lieu_naissance' => 'Fès', 'sexe' => 'F'],
            ['nom' => 'Rachidi', 'prenom' => 'Omar', 'email' => 'rachidi@etu-note.ma', 'date_naissance' => '2004-07-08', 'lieu_naissance' => 'Tanger', 'sexe' => 'M'],
        ];

        foreach ($students as $student) {
            $utilisateur = Utilisateur::create([
                'uuid' => Str::uuid()->toString(),
                'nom' => $student['nom'],
                'prenom' => $student['prenom'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'telephone' => '+212 6 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'etat' => 'actif',
                'email_verified_at' => now(),
            ]);

            $etudiant = Etudiant::create([
                'utilisateur_id' => $utilisateur->id,
                'matricule' => 'MAT' . strtoupper(Str::random(6)),
                'date_naissance' => $student['date_naissance'],
                'lieu_naissance' => $student['lieu_naissance'],
                'sexe' => $student['sexe'],
                'classe_id' => rand(1, 3),
            ]);

            // Create inscription
            Inscription::create([
                'etudiant_id' => $etudiant->id,
                'classe_id' => $etudiant->classe_id,
                'annee_scolaire' => 2025,
                'statut' => 'active',
                'date_inscription' => now(),
            ]);
        }

        // Create some evaluations
        $evaluations = [
            ['matiere_id' => 1, 'classe_id' => 1, 'type' => 'examen', 'description' => 'Examen midterm algo', 'date_evaluation' => '2026-01-15', 'note_max' => 20, 'coefficient' => 1.5],
            ['matiere_id' => 2, 'classe_id' => 1, 'type' => 'devoir', 'description' => 'Devoir SQL', 'date_evaluation' => '2026-01-20', 'note_max' => 20, 'coefficient' => 1],
            ['matiere_id' => 3, 'classe_id' => 1, 'type' => 'examen', 'description' => 'Examen final web', 'date_evaluation' => '2026-02-01', 'note_max' => 20, 'coefficient' => 2],
            ['matiere_id' => 1, 'classe_id' => 2, 'type' => 'examen', 'description' => 'Examen algo L2', 'date_evaluation' => '2026-01-18', 'note_max' => 20, 'coefficient' => 1.5],
            ['matiere_id' => 4, 'classe_id' => 1, 'type' => 'devoir', 'description' => 'Devoir réseaux', 'date_evaluation' => '2026-02-10', 'note_max' => 20, 'coefficient' => 1],
        ];

        foreach ($evaluations as $evaluation) {
            Evaluation::create($evaluation);
        }

        // Create some notes
        $etudiants = Etudiant::all();
        $evals = Evaluation::all();

        foreach ($etudiants as $etudiant) {
            foreach ($evals as $eval) {
                if ($etudiant->classe_id == $eval->classe_id) {
                    Note::create([
                        'etudiant_id' => $etudiant->id,
                        'evaluation_id' => $eval->id,
                        'note' => rand(8, 18) + rand(0, 99) / 100,
                    ]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'code' => 'admin',
                'libelle' => 'Administrateur',
                'description' => 'Accès complet au système, gestion des utilisateurs et configurations',
            ],
                        [
                'code' => 'teacher',
                'libelle' => 'Professeur',
                'description' => 'Saisie et validation des notes pour ses matières assignées',
            ],
            [
                'code' => 'student',
                'libelle' => 'Étudiant',
                'description' => 'Consultation de ses propres notes et bulletins',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                $role
            );
        }
    }
}

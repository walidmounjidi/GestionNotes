<?php

namespace Database\Seeders;

use App\Models\Specialization;
use App\Models\Level;
use App\Models\Classe;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $specs = [
            [
                'name' => 'Informatique',
                'description' => 'Filière Sciences de l\'Informatique - Développement logiciel, réseaux, et systèmes',
                'levels' => ['1ère Année', '2ème Année', '3ème Année'],
            ],
            [
                'name' => 'Mathématiques',
                'description' => 'Filière Mathématiques - Analyse, algèbre, et statistiques',
                'levels' => ['1ère Année', '2ème Année', '3ème Année'],
            ],
            [
                'name' => 'Sciences Économiques',
                'description' => 'Filière Sciences Économiques - Macroéconomie, microéconomie, et gestion',
                'levels' => ['1ère Année', '2ème Année', '3ème Année'],
            ],
            [
                'name' => 'Droit',
                'description' => 'Filière Droit - Droit civil, pénal, et constitutionnel',
                'levels' => ['1ère Année', '2ème Année', '3ème Année', '4ème Année', '5ème Année'],
            ],
        ];

        foreach ($specs as $specData) {
            $spec = Specialization::create([
                'name' => $specData['name'],
                'description' => $specData['description'],
            ]);

            foreach ($specData['levels'] as $order => $levelName) {
                Level::create([
                    'specialization_id' => $spec->id,
                    'name' => $levelName,
                    'order' => $order + 1,
                ]);
            }

            foreach ($specData['levels'] as $order => $levelName) {
                $classeCode = strtoupper(substr($specData['name'], 0, 3)) . ($order + 1) . 'S';
                $levelId = $spec->levels()->where('name', $levelName)->first()->id;
                
                $classe = Classe::firstOrCreate(
                    ['code' => $classeCode],
                    [
                        'libelle' => $levelName . ' ' . $specData['name'],
                        'niveau' => $levelName,
                        'annee_scolaire' => 2026,
                        'specialization_id' => $spec->id,
                        'level_id' => $levelId,
                    ]
                );
            }
        }
    }
}

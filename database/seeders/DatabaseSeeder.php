<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SpecializationSeeder::class,
        ]);

        $admin = Utilisateur::where('email', 'admin@admin.com')->first();

        if (!$admin) {
            $admin = Utilisateur::create([
                'uuid' => Str::uuid()->toString(),
                'nom' => 'Admin',
                'prenom' => 'Système',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'telephone' => '+212 6 00 00 00 00',
                'etat' => 'actif',
                'email_verified_at' => now(),
            ]);
        }

        $adminRole = Role::where('code', 'admin')->first();
        if ($adminRole && !$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        $manager = Utilisateur::where('email', 'manager@manager.com')->first();
        if (!$manager) {
            $manager = Utilisateur::create([
                'uuid' => Str::uuid()->toString(),
                'nom' => 'Manager',
                'prenom' => 'Système',
                'email' => 'manager@manager.com',
                'password' => Hash::make('password'),
                'telephone' => '+212 6 00 00 00 01',
                'etat' => 'actif',
                'email_verified_at' => now(),
            ]);

            $managerRole = Role::where('code', 'manager')->first();
            if ($managerRole) {
                $manager->assignRole($managerRole);
            }
        }
    }
}
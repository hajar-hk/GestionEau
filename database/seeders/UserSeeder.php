<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un Admin
        DB::table('users')->insert([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'identifiant_connexion' => 'admin',
            'password' => Hash::make('password'), // Mot de passe: "password"
            'role' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Créer un Régisseur
        DB::table('users')->insert([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'identifiant_connexion' => 'regisseur',
            'password' => Hash::make('password'), // Mot de passe: "password"
            'role' => 'Regisseur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
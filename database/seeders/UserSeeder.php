<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'identifiant_connexion' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'Admin', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('users')->insert([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'regisseur@example.com',
            'identifiant_connexion' => 'regisseur',
            'password' => Hash::make('password'),
            'role' => 'Régisseur', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

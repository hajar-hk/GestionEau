<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'code_client' => 'C01003',
                'nom_client' => 'Alami',
                'prenom_client' => 'Mohammed',
                'telephone' => '0661123456',
                'secteur' => 'Centre', 
                'email' => 'ahmed.benali@example.com',
                'statut' => 'Actif',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'code_client' => 'C01004',
                'nom_client' => 'Berrada',
                'prenom_client' => 'Fatima',
                'telephone' => '0662234567',
                'secteur' => 'Nord', 
                'email' => 'berrada.fati@example.com',
                'statut' => 'Actif',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'code_client' => 'C01005',
                'nom_client' => 'Cherkaoui',
                'prenom_client' => 'Youssef',
                'telephone' => '0663345678',
                'secteur' => 'Sud', 
                'email' => 'cherkaoui.youss@example.com',
                'statut' => 'Actif',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
};
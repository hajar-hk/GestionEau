<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FactureSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('factures')->insert([
            // Factures pour Client 1 (Mohammed Alami)
            [
                'numero_facture' => 'F-2024-001',
                'montants' => 1250,
                'date_emission' => '2024-01-15', 'date_echeance' => '2024-02-15',
                'statut' => 'Non Payée', 'client_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'numero_facture' => 'F-2024-002',
                'montants' => 666.67, 
                'date_emission' => '2024-02-20', 'date_echeance' => '2024-03-20',
                'statut' => 'Non Payée', 'client_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            // Facture pour Client 2 (Fatima Berrada)
            [
                'numero_facture' => 'F-2024-003',
                'montants' => 2000, 
                'date_emission' => '2024-03-01', 'date_echeance' => '2024-04-01',
                'statut' => 'Non Payée', 'client_id' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            // Facture pour Client 1 (Mohammed Alami)
            [
                'numero_facture' => 'F-2024-004',
                'montants' => 791.67,
                'date_emission' => '2024-03-10', 'date_echeance' => '2024-04-10',
                'statut' => 'Payée', 'client_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
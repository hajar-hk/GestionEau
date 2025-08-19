<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FactureSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 20 factures aléatoires pour des clients aléatoires
        \App\Models\Facture::factory(20)->create();
    }
}

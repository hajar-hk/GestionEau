<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Declaration;
use App\Models\BordereauRg12;

class DeclarationSeeder extends Seeder
{
    public function run(): void
    {
        // On cherche SEULEMENT le premier bordereau disponible
        $bordereauLibre = BordereauRg12::whereNull('declaration_id')->first();

        // S'il existe, on crée UNE SEULE déclaration pour lui
        if ($bordereauLibre) {

            // On stocke le résultat de create() dans la variable $declaration
            $declaration = Declaration::create([
                'numero_declaration' => 'DECL-SEED-001',
                'periode' => 'Janvier 2024',
                'montant_global' => $bordereauLibre->montant_total,
                'date_declaration' => '2024-01-31',
                'statut' => 'Validée',
            ]);

            // On le lie en utilisant la variable qu'on vient de créer
            $bordereauLibre->update(['declaration_id' => $declaration->id]);
        }
    }
}

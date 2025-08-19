<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaiementRG8;
use App\Models\BordereauRg12;

class BordereauRg12Seeder extends Seeder
{
    public function run(): void
    {
        // On cherche les 2 PREMIERS paiements libres
        $deuxPaiements = PaiementRG8::whereNull('bordereau_rg12_id')->take(2)->get();

        if ($deuxPaiements->count() >= 2) {
            $montantTotal = $deuxPaiements->sum('montant_paye');
            $bordereau1 = BordereauRg12::create([
                'numero_rg12' => 'RG12-SEED-001',
                'montant_total' => $montantTotal,
                'date_creation' => now(),
            ]);
            // On lie ces 2 paiements au bordereau
            foreach ($deuxPaiements as $paiement) {
                $paiement->update(['bordereau_rg12_id' => $bordereau1->id]);
            }
        }

        // On cherche UN AUTRE paiement libre (le 3ème)
        $unAutrePaiement = PaiementRG8::whereNull('bordereau_rg12_id')->first();
        if ($unAutrePaiement) {
            $bordereau2 = BordereauRg12::create([
                'numero_rg12' => 'RG12-SEED-002',
                'montant_total' => $unAutrePaiement->montant_paye,
                'date_creation' => now(),
            ]);
            // On lie ce paiement au 2ème bordereau
            $unAutrePaiement->update(['bordereau_rg12_id' => $bordereau2->id]);
        }
    }
}

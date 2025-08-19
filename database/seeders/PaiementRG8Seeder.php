<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facture;
use App\Models\PaiementRG8;
use App\Models\User;

class PaiementRG8Seeder extends Seeder
{
    public function run(): void
    {
        // On cherche le régisseur
        $regisseur = User::where('role', 'Régisseur')->first();

        // On cherche les factures qui ne sont pas "En attente"
        $facturesAPayer = Facture::whereIn('statut', ['Payée', 'En retard'])->get();

        foreach ($facturesAPayer as $facture) {
            // On vérifie si un paiement n'existe pas déjà pour cette facture
            if (!PaiementRG8::where('facture_id', $facture->id)->exists()) {
                PaiementRG8::create([
                    'numero_rg8' => 'RG8-' . $facture->id . '-' . time(),
                    'numero_recu' => 'REC-' . $facture->id,
                    'methode_reglement' => 'Espèce',
                    'montant_paye' => $facture->montants,
                    'penalite_retard' => ($facture->statut === 'En retard' ? 50.00 : 0.00),
                    'date_paiement' => now(),
                    'user_id' => $regisseur->id,
                    'facture_id' => $facture->id,
                ]);
            }
        }
        // On crée un paiement supplémentaire qui restera libre (sans bordereau)
        $factureLibre = Facture::where('statut', 'En attente')->first();
        if ($factureLibre) {
            PaiementRG8::create([
                'numero_rg8' => 'RG8-LIBRE-001',
                'numero_recu' => 'REC-LIBRE',
                'methode_reglement' => 'Versement',
                'montant_paye' => $factureLibre->montants,
                'penalite_retard' => 0.00,
                'date_paiement' => now(),
                'user_id' => User::where('role', 'Régisseur')->first()->id,
                'facture_id' => $factureLibre->id,
            ]);
            // On met à jour la facture comme Payée
            $factureLibre->update(['statut' => 'Payée']);
        }
    }
}

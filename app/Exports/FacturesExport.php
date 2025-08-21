<?php

namespace App\Exports;

use App\Models\Facture;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Pour les en-têtes
use Maatwebsite\Excel\Concerns\WithMapping;  // Pour formater les données

class FacturesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // On récupère les factures avec les infos du client
        return Facture::with('client')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Noms des colonnes dans le fichier Excel
        return [
            'Numéro Facture',
            'Code Client',
            'Nom Client',
            'Montant TTC',
            'Statut',
            'Semestre',
            'Date Émission',
            'Date Échéance',
        ];
    }

    /**
     * @param mixed $facture
     * @return array
     */
    public function map($facture): array
    {
        // On formate chaque ligne du fichier Excel
        return [
            $facture->numero_facture,
            $facture->client->code_client ?? 'N/A',
            ($facture->client->nom_client ?? '') . ' ' . ($facture->client->prenom_client ?? ''),
            $facture->montants,
            $facture->statut,
            $facture->semestre,
            $facture->date_emission,
            $facture->date_echeance,
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BordereauRg12;
use App\Models\PaiementRG8;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RG12Controller extends Controller
{
    /**
     * Show the form for creating a new resource.
     */


    public function create()
    {
        $paiementsDisponibles = PaiementRG8::whereNull('bordereau_rg12_id')
            ->with('facture.client')
            ->latest('date_paiement')
            ->get();

        $rg12Recents = BordereauRg12::latest()->take(5)->get();

        return view('rg12.create', [
            'paiements' => $paiementsDisponibles,
            'rg12Recents' => $rg12Recents,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation avec messages personnalisés
        $request->validate([
            'numero_rg12' => 'required|string|unique:bordereaux_rg12,numero_rg12',
            'paiement_ids' => 'required|array|min:1',
            'paiement_ids.*' => 'exists:paiements_rg8,id',
        ], [
            'numero_rg12.required' => 'Le champ Numéro RG12 est obligatoire.',
            'numero_rg12.unique' => 'Ce numéro RG12 existe déjà.',
            'paiement_ids.required' => 'Vous devez sélectionner au moins un paiement.',
        ]);

        // On utilise une transaction pour la sécurité
        DB::beginTransaction();
        try {
            // 2. N'7sbo l'montant total
            $paiements = PaiementRG8::whereIn('id', $request->paiement_ids)->get();
            $montantTotal = $paiements->sum('montant_paye');

            // 3. N'créyiw l'Bordereau RG12
            $bordereau = BordereauRg12::create([
                'numero_rg12' => $request->numero_rg12,
                'montant_total' => $montantTotal,
                'date_creation' => now(),
                'declaration_id' => null, // On le laisse null pour l'instant
            ]);

            // 4. N'rbto les paiements b had l'bordereau
            PaiementRG8::whereIn('id', $request->paiement_ids)
                ->update(['bordereau_rg12_id' => $bordereau->id]);

            DB::commit();

            return redirect()->route('rg12.create')->with('success', 'Bordereau RG12 créé avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur critique: ' . $e->getMessage())->withInput();
        }
    }

    public function print(BordereauRg12 $bordereau)
    {
        // On charge les relations pour avoir les paiements et leurs détails
        $bordereau->load(['paiements.facture.client', 'paiements.user']);

        // On passe les données à une nouvelle vue PDF
        $pdf = PDF::loadView('rg12.pdf', compact('bordereau'));

        // On affiche le PDF dans le navigateur
        return $pdf->stream('rg12-' . $bordereau->numero_rg12 . '.pdf');
    }
}

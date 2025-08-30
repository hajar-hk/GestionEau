<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaiementRG8;
use App\Models\Facture;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les paiements avec les infos du client et du régisseur
        $paiements = PaiementRG8::with(['facture.client', 'user'])->latest()->get();

        // Calcul des statistiques
        $totalRG8 = $paiements->count();
        $actifs = $paiements->where('statut', 'Actif')->count();
        $modifies = $paiements->where('statut', 'Modifié')->count();
        $annules = $paiements->where('statut', 'Annulé')->count();

        return view('paiements.index', compact(
            'paiements',
            'totalRG8',
            'actifs',
            'modifies',
            'annules'
        ));
    }

    // c'est pour la gestion de la generation du pdf 
    public function print(PaiementRG8 $paiement)
    {
        // On charge les relations pour avoir toutes les infos
        $paiement->load(['facture.client', 'user']);

        // On passe les données à une nouvelle vue PDF
        $pdf = PDF::loadView('paiements.pdf', compact('paiement'));

        // On affiche le PDF dans le navigateur
        return $pdf->stream('rg8-' . $paiement->numero_rg8 . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaiementRG8 $paiement)
    {
        // On charge les relations pour avoir toutes les infos
        $paiement->load(['facture.client', 'user']);
        return view('paiements.show', compact('paiement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaiementRG8 $paiement)
    {
        // On a besoin des factures et des régisseurs pour les dropdowns
        $factures = Facture::all();
        $regisseurs = User::where('role', 'Régisseur')->get();

        return view('paiements.edit', compact('paiement', 'factures', 'regisseurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaiementRG8 $paiement)
    {
        $validatedData = $request->validate([
            'numero_recu' => 'required|string',
            'methode_reglement' => 'required|string',
            'montant_paye' => 'required|numeric',
            'penalite_retard' => 'required|numeric',
            'date_paiement' => 'required|date',
            'statut' => 'required|string',
        ]);

        $paiement->update($validatedData);

        // On change le statut à 'Modifié'
        $paiement->statut = 'Modifié';
        $paiement->save();

        return redirect()->route('paiements.index')->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaiementRG8 $paiement)
    {
        // Au lieu de supprimer, on change le statut
        $paiement->statut = 'Annulé';


        $paiement->save();

        // On doit aussi remettre le statut de la facture à "En attente"
        $facture = $paiement->facture;
        if ($facture) {
            $facture->statut = 'En attente';
            $facture->save();
        }

        return redirect()->route('paiements.index')->with('success', 'Le paiement a été annulé.');
    }
}

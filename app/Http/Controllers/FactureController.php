<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. On commence avec une requête de base qui inclut le client
        $query = Facture::query()->with('client');

        // 2. Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // 3. Filtre par semestre
        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        // 4. Filtre par barre de recherche (LA PARTIE LA PLUS IMPORTANTE)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Recherche sur le numéro de facture
                $q->where('numero_facture', 'like', "%{$search}%")
                    // OU recherche sur la table des clients associés
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('code_client', 'like', "%{$search}%")
                            ->orWhere('nom_client', 'like', "%{$search}%")
                            ->orWhere('prenom_client', 'like', "%{$search}%")
                            // Bonus: Recherche sur le nom complet
                            ->orWhere(DB::raw("CONCAT(nom_client, ' ', prenom_client)"), 'like', "%{$search}%");
                    });
            });
        }

        // 5. On exécute la requête finale et on trie
        $factures = $query->latest()->get();

        // Les statistiques globales (ne changent pas avec le filtre)
        $totalFactures = Facture::count();
        $totalPayees = Facture::where('statut', 'Payée')->count();
        $totalEnAttente = Facture::where('statut', 'En attente')->count();
        $totalEnRetard = Facture::where('statut', 'En retard')->count();
        $montantTotal = Facture::sum('montants');

        return view('factures.index', [
            'factures' => $factures, // On envoie les factures filtrées
            'totalFactures' => $totalFactures,
            'totalPayees' => $totalPayees,
            'totalEnAttente' => $totalEnAttente,
            'totalEnRetard' => $totalEnRetard,
            'montantTotal' => $montantTotal,
        ]);
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
    public function show(Facture $facture)
    {
        return view('factures.show', ['facture' => $facture]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // AFFICHER LE FORMULAIRE DE MODIFICATION
    public function edit(Facture $facture)
    {
        // On a besoin de la liste de tous les clients pour le dropdown
        $clients = Client::orderBy('nom_client')->get();

        // On retourne une vue avec la facture à modifier et la liste des clients
        return view('factures.edit', [
            'facture' => $facture,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // ENREGISTRER LA MODIFICATION
    public function update(Request $request, Facture $facture)
    {
        $validatedData = $request->validate([
            'numero_facture' => 'required|string|unique:factures,numero_facture,' . $facture->id,
            'client_id' => 'required|exists:clients,id',
            'montants' => 'required|numeric|min:0',
            'date_emission' => 'required|date',
            'date_echeance' => 'required|date',
            'semestre' => 'required|string',
            'statut' => 'required|string',
        ]);

        $facture->update($validatedData);


        return redirect()->route('factures.index')->with('success', 'Facture mise à jour avec succès!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

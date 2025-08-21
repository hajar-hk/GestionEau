<?php

namespace App\Http\Controllers;


use App\Models\Client;
use App\Models\Facture;
use App\Models\PaiementRG8;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;





class Rg8Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. N'jibo ga3 les clients men la base de données
        $clients = Client::all();

        // 2. N'jibo ghir les factures li baqin ma tkhlsouch ('Non Payée')
        $facturesDisponibles = Facture::where('statut', 'Non Payée')->get();

        // 3. N'sifto had les données l'la vue dialna
        return view('rg8.create', [
            'clients' => $clients,
            'factures' => $facturesDisponibles,
        ]);
    }


    // la fontion de recherche 
    public function searchClient(Request $request)
    {
        $query = $request->input('query');

        $client = Client::where('code_client', $query)->first();

        if ($client) {

            // On cherche les factures qui sont soit 'En attente', soit 'En retard'
            $factures = Facture::where('client_id', $client->id)
                ->whereIn('statut', ['En attente', 'En retard'])
                ->get();


            return response()->json([
                'client' => $client,
                'factures' => $factures,
            ]);
        }

        return response()->json(['error' => 'Client non trouvé'], 404);
    }



    // pour gette les donner mane navigat w ydkhlo base donnee
    public function store(Request $request)
    {
        // 1. Validation (sans numero_rg8)
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_operation' => 'required|date',
            'numero_recu' => 'required|string|max:255',
            'methode_reglement' => 'required|string',
            'factures_ids' => 'required|array|min:1',
            'factures_ids.*' => 'exists:factures,id',
        ]);

        DB::beginTransaction();
        try {
            // Boucle pour garantir un numéro RG8 unique
            foreach ($validatedData['factures_ids'] as $factureId) {
                // Générer un numéro unique pour CHAQUE facture
                do {
                    $numeroRg8 = 'RG8-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                } while (PaiementRG8::where('numero_rg8', $numeroRg8)->exists());

                $facture = Facture::find($factureId);
                if ($facture) {
                    $paiement = PaiementRG8::create([
                        'numero_rg8' => $numeroRg8,
                        'numero_recu' => $validatedData['numero_recu'],
                        'methode_reglement' => $validatedData['methode_reglement'],
                        'montant_paye' => $facture->montants,
                        'date_paiement' => $validatedData['date_operation'],
                        'bordereau_rg12_id' => null,
                        'user_id' => Auth::id(),
                        'facture_id' => $facture->id,
                        'statut' => 'Actif',
                    ]);

                    \App\Models\Activity::create([
                        'user_id' => Auth::id(), // Le régisseur qui a fait l'action
                        'action' => 'payment_created',
                        'description' => "Le paiement {$paiement->numero_rg8} pour le client {$facture->client->code_client} a été enregistré."
                    ]);
                    $facture->statut = 'Payée';
                    $facture->save();
                }
            }

            DB::commit();
            return redirect()->route('rg8.create')->with('success', 'Paiement enregistré avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

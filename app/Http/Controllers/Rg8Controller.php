<?php

namespace App\Http\Controllers;

 
use App\Models\Client;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 


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
    // 1. N'chdo l'term dyal l'recherche li ja men l'URL (ex: ?query=C01003)
    $query = $request->input('query');

    // 2. N'9lbo 3la client b l'code dialo
    $client = Client::where('code_client', $query)->first();

    // 3. Ila lqinah...
    if ($client) {
        // 4. N'jibo les factures dialo li Non Payée
        $factures = Facture::where('client_id', $client->id)
                            ->where('statut', 'Non Payée')
                            ->get();
        
        // 5. N'rje3o les données f format JSON
        return response()->json([
            'client' => $client,
            'factures' => $factures,
        ]);
    }

    // 6. Ila ma lqinahch, n'rje3o erreur
    return response()->json(['error' => 'Client non trouvé'], 404);
    }



   // pour gette les donner mane navigat w ydkhlo base donnee
    public function store(Request $request)
   {
    // 1. Validation des données (important pour la sécurité)
    $validatedData = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'date_operation' => 'required|date',
        'numero_recu' => 'required|string|max:255',
        'numero_rg8' => 'required|string|max:255|unique:paiements_rg8,numero_rg8',
        'methode_reglement' => 'required|string',
        'factures_ids' => 'required|array',
        'factures_ids.*' => 'exists:factures,id', // T2eked anaho kol id kayn f la table factures
    ]);

    // L'logique dyal Bordereau/Déclaration ghadi nzidooh men be3d.
    // Daba ghadi n'supposiw 3ndna un Bordereau avec l'id = 1 (pour test)
    // **A FAIRE PLUS TARD: Créer le bordereau et la déclaration dynamiquement**
    $bordereauId = 1; 

    // Pour la simplicité, on va créer un paiement pour chaque facture
    // On utilise une transaction pour s'assurer que tout se passe bien, ou rien ne se passe
    DB::beginTransaction();
    try {
        
        // 2. Boucler sur chaque facture payée
        foreach ($validatedData['factures_ids'] as $factureId) {
            
            $facture = Facture::find($factureId);
            if ($facture) {
                // 3. Créer un enregistrement de paiement
                // **A FAIRE PLUS TARD: Lier le paiement au bon bordereau**
                
                \App\Models\PaiementRG8::create([
                    'numero_rg8' => $validatedData['numero_rg8'],
                    'numero_recu' => $validatedData['numero_recu'],
                    'methode_reglement' => $validatedData['methode_reglement'],
                    'montant_paye' => $facture->montants,
                    'date_paiement' => $validatedData['date_operation'],
                    'bordereau_rg12_id' => null, // temporaire
                    'user_id' => 1, // L'ID de l'utilisateur connecté
                    'facture_id' => $facture->id,
                ]);
                

                // 4. Mettre à jour le statut de la facture
                $facture->statut = 'Payée';
                $facture->save();
            }
        }

        DB::commit(); // Tout s'est bien passé, on sauvegarde les changements

        // 5. Rediriger avec un message de succès
        return redirect()->route('rg8.create')->with('success', 'Paiement enregistré avec succès!');

    } catch (\Exception $e) {
        DB::rollBack(); // Annuler tous les changements s'il y a une erreur
        // Pour le débogage, on peut afficher l'erreur
        // return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        return back()->with('error', 'Une erreur est survenue lors de l`enregistrement.'.$e->getMessage());
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
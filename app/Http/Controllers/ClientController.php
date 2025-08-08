<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $clients = Client::latest()->get();

    // Calcul des statistiques
    $totalClients = $clients->count();
    $clientsActifs = $clients->where('statut', 'Actif')->count();
    $clientsInactifs = $clients->where('statut', 'Inactif')->count();

    return view('clients.index', [
        'clients' => $clients,
        'totalClients' => $totalClients,
        'clientsActifs' => $clientsActifs,
        'clientsInactifs' => $clientsInactifs,
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
        // 1. Validation
        $validatedData = $request->validate([
            'code_client' => 'required|string|unique:clients,code_client',
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'nullable|string',
            'secteur' => 'nullable|string',
            'statut' => 'required|string',
        ]);

        // 2. Création
        Client::create($validatedData);

        // 3. Redirection
        return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
{
    return view('clients.show', ['client' => $client]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
{
    return response()->json($client);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
{
    $validatedData = $request->validate([
        'code_client' => 'required|string|unique:clients,code_client,' . $client->id,
        'nom_client' => 'required|string|max:255',
        'prenom_client' => 'required|string|max:255',
        'email' => 'required|email|unique:clients,email,' . $client->id,
        'telephone' => 'nullable|string',
        'secteur' => 'nullable|string',
        'statut' => 'required|string',
    ]);
    
    $client->update($validatedData);

    return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
{
    // On peut ajouter une vérification si le client a des factures
    $client->delete();
    return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès!');
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\BordereauRg12;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeclarationController extends Controller
{

    public function index()
    {
        return redirect()->route('declarations.create');
    }

    /**
     * Affiche le formulaire de création ET l'historique.
     */
    public function create()
    {
        // 1. On récupère les bordereaux disponibles pour le formulaire
        $bordereauxDisponibles = BordereauRg12::whereNull('declaration_id')->latest()->get();

        // 2. On récupère les déclarations récentes pour l'historique
        $declarationsRecentes = Declaration::latest()->take(5)->get();

        // 3. On calcule les statistiques
        $allDeclarations = Declaration::all();
        $declarationsSoumises = $allDeclarations->count();
        $totalDeclare = $allDeclarations->sum('montant_global');
        $declarationsValidees = $allDeclarations->where('statut', 'Validée')->count();
        $tauxValidation = ($declarationsSoumises > 0) ? ($declarationsValidees / $declarationsSoumises) * 100 : 0;

        // 4. On envoie toutes les données à la vue de création
        return view('declarations.create', [
            'bordereauxDisponibles' => $bordereauxDisponibles,
            'declarationsRecentes' => $declarationsRecentes,
            'declarationsSoumises' => $declarationsSoumises,
            'totalDeclare' => $totalDeclare,
            'tauxValidation' => round($tauxValidation),
        ]);
    }

    /**
     * Enregistre une nouvelle déclaration.
     */
    public function store(Request $request)
    {
        // 1. Validation des données du formulaire
        $validatedData = $request->validate([
            'numero_declaration' => 'required|string|unique:declarations,numero_declaration',
            'periode' => 'required|string|max:255',
            'date_declaration' => 'required|date',
            'bordereau_ids' => 'required|array|min:1',
            'bordereau_ids.*' => 'exists:bordereaux_rg12,id',
        ], [
            'numero_declaration.required' => 'Le numéro de déclaration est obligatoire.',
            'numero_declaration.unique' => 'Ce numéro de déclaration existe déjà.',
            'bordereau_ids.required' => 'Vous devez sélectionner au moins un bordereau RG12.',
        ]);

        DB::beginTransaction();
        try {
            // 2. Calculer le montant total à partir des bordereaux sélectionnés
            $bordereaux = BordereauRg12::whereIn('id', $validatedData['bordereau_ids'])->get();
            $montantTotal = $bordereaux->sum('montant_total');

            // 3. Créer la nouvelle déclaration
            $declaration = Declaration::create([
                'numero_declaration' => $validatedData['numero_declaration'],
                'periode' => $validatedData['periode'],
                'montant_global' => $montantTotal,
                'date_declaration' => $validatedData['date_declaration'],
                'statut' => 'Soumise', // Le statut par défaut est "Soumise"
            ]);

            // 4. Lier les bordereaux sélectionnés à cette nouvelle déclaration
            BordereauRg12::whereIn('id', $validatedData['bordereau_ids'])
                ->update(['declaration_id' => $declaration->id]);

            DB::commit();

            // 5. Rediriger avec un message de succès
            return redirect()->route('declarations.create')->with('success', 'La déclaration a été créée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            // Rediriger avec le message d'erreur pour le débogage
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage())->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Facture;
use App\Models\PaiementRG8;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // On vérifie le rôle de l'utilisateur connecté
        $user = Auth::user();

        // On récupère les 5 dernières activités globales
        $recentActivities = \App\Models\Activity::with('user')->latest()->take(5)->get();

        if ($user->role === 'Admin') {
            // Si c'est un Admin, on prépare les données pour le dashboard Admin

            // Revenus totaux (tous les paiements)
            $revenusTotaux = PaiementRG8::sum('montant_paye');

            // Taux de recouvrement (Payées / Total)
            $totalFactures = Facture::count();
            $facturesPayees = Facture::where('statut', 'Payée')->count();
            $tauxRecouvrement = ($totalFactures > 0) ? ($facturesPayees / $totalFactures) * 100 : 0;

            // Créances en cours (factures non payées)
            $creancesEnCours = Facture::whereIn('statut', ['En attente', 'En retard'])->sum('montants');

            // Retards de paiement (nombre de factures en retard)
            $retardsDePaiement = Facture::where('statut', 'En retard')->count();

            return view('admin.dashboard', [
                'revenusTotaux' => $revenusTotaux,
                'tauxRecouvrement' => round($tauxRecouvrement),
                'creancesEnCours' => $creancesEnCours,
                'retardsDePaiement' => $retardsDePaiement,
                'recentActivities' => $recentActivities,
            ]);
        } else {
            // Si c'est un Régisseur , on prépare les données pour son dashboard

            $paiementsDuMois = PaiementRG8::where('user_id', $user->id) // Uniquement ses paiements
                ->whereMonth('date_paiement', Carbon::now()->month)
                ->count();

            $facturesEnAttente = Facture::where('statut', 'En attente')->count();
            $clientsActifs = Client::where('statut', 'Actif')->count();
            $recouvrementMensuel = PaiementRG8::where('user_id', $user->id) // Uniquement son recouvrement
                ->whereMonth('date_paiement', Carbon::now()->month)
                ->sum('montant_paye');

            return view('dashboard', [
                'paiementsDuMois' => $paiementsDuMois,
                'facturesEnAttente' => $facturesEnAttente,
                'clientsActifs' => $clientsActifs,
                'recouvrementMensuel' => $recouvrementMensuel,
                'recentActivities' => $recentActivities,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaiementRG8;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Client;


class StatistiqueController extends Controller
{
    public function index(Request $request)
    {
        // === 1. GESTION DES FILTRES DE DATE ===
        $startDate = Carbon::createFromTimestamp(0);
        $endDate = now();
        $periodeSelectionnee = $request->get('periode', 'all');

        if ($periodeSelectionnee != 'all') {
            switch ($periodeSelectionnee) {
                case 'this_month':
                    $startDate = Carbon::now()->startOfMonth();
                    break;
                case 'last_month':
                    $startDate = Carbon::now()->subMonthNoOverflow()->startOfMonth();
                    $endDate = Carbon::now()->subMonthNoOverflow()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = Carbon::now()->startOfYear();
                    break;
            }
        }

        // === 2. CRÉATION DES REQUÊTES DE BASE FILTRÉES ===
        $paiementsQuery = PaiementRG8::whereBetween('date_paiement', [$startDate, $endDate]);
        $facturesQuery = Facture::whereBetween('created_at', [$startDate, $endDate]); // On filtre les factures par date de création

        // === 3. CALCUL DES KPIs À PARTIR DES REQUÊTES FILTRÉES ===
        $revenusTotaux = (clone $paiementsQuery)->sum(DB::raw('montant_paye + penalite_retard'));
        $totalFactures = (clone $facturesQuery)->count();
        $facturesPayees = (clone $facturesQuery)->where('statut', 'Payée')->count();
        $tauxRecouvrement = ($totalFactures > 0) ? ($facturesPayees / $totalFactures) * 100 : 0;
        $creancesEnCours = (clone $facturesQuery)->whereIn('statut', ['En attente', 'En retard'])->sum('montants');
        $retardsDePaiement = (clone $facturesQuery)->where('statut', 'En retard')->count();

        // === NOUVEAU: On récupère la liste des secteurs pour le dropdown ===
        $secteurs = Client::select('secteur')->whereNotNull('secteur')->distinct()->orderBy('secteur')->pluck('secteur');

        // === On modifie les requêtes de base pour inclure le filtre secteur ===
        $paiementsQuery = PaiementRG8::whereBetween('date_paiement', [$startDate, $endDate]);
        $facturesQuery = Facture::whereBetween('created_at', [$startDate, $endDate]);

        // On applique le filtre secteur s'il est présent
        if ($request->filled('secteur')) {
            // On doit filtrer les factures ET les paiements par secteur
            $facturesQuery->whereHas('client', function ($q) use ($request) {
                $q->where('secteur', $request->secteur);
            });
            $paiementsQuery->whereHas('facture.client', function ($q) use ($request) {
                $q->where('secteur', $request->secteur);
            });
        }

        // === 4. DONNÉES POUR LES GRAPHIQUES (FILTRÉES AUSSI) ===

        // --- Graphique 1: Revenus par Mois ---
        // Ce graphique doit TOUJOURS afficher les 12 derniers mois, il n'est pas affecté par le filtre
        $revenusParMois = PaiementRG8::select(DB::raw('YEAR(date_paiement) as annee, MONTH(date_paiement) as mois, SUM(montant_paye) as total'))
            ->where('date_paiement', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')->orderBy('annee', 'asc')->orderBy('mois', 'asc')->get();
        $labelsMois = $revenusParMois->map(fn($item) => Carbon::create()->month($item->mois)->translatedFormat('F Y'));
        $dataMois = $revenusParMois->pluck('total');

        // --- Graphique 2: Répartition par Secteur (filtré par la période choisie) ---
        $revenusParSecteur = (clone $paiementsQuery) // On utilise la requête déjà filtrée
            ->join('factures', 'paiements_rg8.facture_id', '=', 'factures.id')
            ->join('clients', 'factures.client_id', '=', 'clients.id')
            ->select('clients.secteur', DB::raw('SUM(paiements_rg8.montant_paye + paiements_rg8.penalite_retard) as total'))
            ->groupBy('clients.secteur')->pluck('total', 'secteur');
        $labelsSecteur = $revenusParSecteur->keys();
        $dataSecteur = $revenusParSecteur->values();

        // === 5. ENVOI DE TOUTES LES DONNÉES À LA VUE ===
        return view('statistiques.index', compact(
            'revenusTotaux',
            'tauxRecouvrement',
            'creancesEnCours',
            'retardsDePaiement',
            'labelsMois',
            'dataMois',
            'labelsSecteur',
            'dataSecteur',
            'secteurs',
        ));
    }
}

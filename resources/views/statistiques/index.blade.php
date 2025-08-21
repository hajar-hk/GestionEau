@extends('layouts.app')
@section('title', 'Statistiques de Paiement')
@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center"><i
                class="fas fa-chart-pie mr-3 text-cyan-500"></i>Statistiques de Paiement</h1>
        <p class="text-gray-500">Aperçu global de la performance du recouvrement</p>
    </div>

    {{-- Filtres --}}
    <div class="bg-white p-6 rounded-xl shadow-sm mb-8">
        <form action="{{ route('statistiques.index') }}" method="GET">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                {{-- La partie gauche des filtres --}}
                <div class="flex-grow grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Filtre par Période --}}
                    <div>
                        <label for="periode" class="block text-sm font-medium text-gray-700">Période</label>
                        <select name="periode" id="periode"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm">
                            <option value="all" @if (request('periode') == 'all') selected @endif>Depuis le début
                            </option>
                            <option value="this_month" @if (request('periode') == 'this_month') selected @endif>Ce mois-ci</option>
                            <option value="last_month" @if (request('periode') == 'last_month') selected @endif>Le mois dernier
                            </option>
                            <option value="this_year" @if (request('periode') == 'this_year') selected @endif>Cette année</option>
                        </select>
                    </div>
                    {{-- ########## DROPDOWN JDID DYAL SECTEUR ########## --}}
                    <div>
                        <label for="secteur" class="block text-sm font-medium text-gray-700">Secteur</label>
                        <select name="secteur" id="secteur"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm">
                            <option value="">Tous les secteurs</option>
                            @foreach ($secteurs as $secteur)
                                <option value="{{ $secteur }}" @if (request('secteur') == $secteur) selected @endif>
                                    {{ $secteur }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- ############################################# --}}
                </div>

                {{-- La partie droite des boutons --}}
                <div class="flex items-center gap-4 mt-4 md:mt-0">
                    <a href="{{ route('statistiques.index') }}"
                        class="w-full md:w-auto text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Réinitialiser
                    </a>
                    <button type="submit"
                        class="w-full md:w-auto text-center bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">
                        Appliquer
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Revenus totaux</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($revenusTotaux, 2, ',', ' ') }} <span
                    class="text-lg">MAD</span></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Taux de recouvrement</p>
            <p class="text-3xl font-bold">{{ $tauxRecouvrement }}%</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Montant des Créances</p>
            <p class="text-3xl font-bold text-orange-500">{{ number_format($creancesEnCours, 2, ',', ' ') }} <span
                    class="text-lg">MAD</span></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Factures en Retard</p>
            <p class="text-3xl font-bold text-red-500">{{ $retardsDePaiement }}</p>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Graphique 1: Bar Chart --}}
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Revenus par Mois</h2>
            {{-- HNA FIN GHAYT'RSSEM L'GRAPHIQUE --}}
            <canvas id="revenusMoisChart"></canvas>
        </div>
        {{-- Graphique 2: Pie Chart --}}
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Répartition par Secteur</h2>
            <canvas id="secteurChart"></canvas>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Graphique 1: Revenus par Mois ---
                const ctxMois = document.getElementById('revenusMoisChart').getContext('2d');
                new Chart(ctxMois, {
                    type: 'bar', // Type de graphique
                    data: {
                        labels: {!! json_encode($labelsMois) !!}, // Les mois
                        datasets: [{
                            label: 'Revenus en MAD',
                            data: {!! json_encode($dataMois) !!}, // Les montants
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // --- Graphique 2: Répartition par Secteur ---
                const ctxSecteur = document.getElementById('secteurChart').getContext('2d');
                new Chart(ctxSecteur, {
                    type: 'pie', // Type de graphique
                    data: {
                        labels: {!! json_encode($labelsSecteur) !!}, // Les secteurs
                        datasets: [{
                            label: 'Répartition des revenus',
                            data: {!! json_encode($dataSecteur) !!}, // Les montants
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                            ],
                        }]
                    }
                });
            });
        </script>
    @endpush
@endsection

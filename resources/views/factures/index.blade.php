@extends('layouts.app')

@section('title', 'Gestion des Factures')

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-file-invoice mr-3 text-purple-500"></i>
            Gestion des Factures
        </h1>
        <p class="text-gray-500">Consultez et gérez les factures d'irrigation</p>
    </div>

    {{-- Messages de session --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Succès</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Erreur</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Total factures</p>
            <p class="text-2xl font-bold">{{ $totalFactures }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Payées</p>
            <p class="text-2xl font-bold text-green-500">{{ $totalPayees }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">En attente</p>
            <p class="text-2xl font-bold text-orange-500">{{ $totalEnAttente }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">En retard</p>
            <p class="text-2xl font-bold text-red-500">{{ $totalEnRetard }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm col-span-2 md:col-span-1">
            <p class="text-sm text-gray-500">Montant total</p>
            <p class="text-2xl font-bold">{{ number_format($montantTotal, 2, ',', ' ') }} MAD</p>
        </div>
    </div>

    {{-- Filtres et Table Section --}}
    {{-- Filtres --}}
    <div class="bg-white p-6 rounded-xl shadow-sm mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Filtres et recherche</h2>

        <form id="filters-form" action="{{ route('factures.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Recherche --}}
                <div class="md:col-span-3">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par numéro, client..."
                            class="filter-input-search w-full p-2 pl-10 border border-gray-300 rounded-md">
                    </div>
                </div>

                {{-- Statut --}}
                <div>
                    <select name="statut" class="filter-input w-full p-2 border border-gray-300 rounded-md bg-white">
                        <option value="">Tous les statuts</option>
                        <option value="En attente" @if (request('statut') == 'En attente') selected @endif>En attente</option>
                        <option value="Payée" @if (request('statut') == 'Payée') selected @endif>Payée</option>
                        <option value="En retard" @if (request('statut') == 'En retard') selected @endif>En retard</option>
                        <option value="Annulée" @if (request('statut') == 'Annulée') selected @endif>Annulée</option>
                    </select>
                </div>

                {{-- Semestre --}}
                <div>
                    <select name="semestre" class="filter-input w-full p-2 border border-gray-300 rounded-md bg-white">
                        <option value="">Tous les semestres</option>
                        <option value="S1 2024" @if (request('semestre') == 'S1 2024') selected @endif>S1 2024</option>
                        <option value="S2 2023" @if (request('semestre') == 'S2 2023') selected @endif>S2 2023</option>
                        <option value="S1 2025" @if (request('semestre') == 'S1 2025') selected @endif>S1 2025</option>
                        <option value="S2 2025" @if (request('semestre') == 'S2 2025') selected @endif>S2 2025</option>


                    </select>
                </div>

                <div class="text-right">
                    <a href="{{ route('factures.index') }}"
                        class="text-sm text-gray-600 hover:text-gray-900">Réinitialiser</a>
                </div>

            </div>
        </form>
    </div>


    {{-- Table --}}
    <div>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Liste des factures</h2>
                <p class="text-sm text-gray-500">{{ $factures->count() }} facture(s) trouvée(s)</p>
            </div>

            {{--  BOUTON D'EXPORTATION  --}}
            <a href="{{ route('factures.export') }}"
                class="border px-3 py-2 rounded-md text-sm hover:bg-gray-50 flex items-center">
                <i class="fas fa-download mr-2"></i>
                Exporter
            </a>
        </div>


        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Numéro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semestre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'échéance
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($factures as $facture)
                        <tr>
                            <td class="px-6 py-4 font-mono text-sm">{{ $facture->numero_facture }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $facture->client->nom_client }}
                                    {{ $facture->client->prenom_client }}</div>
                                <div class="text-xs text-gray-500">{{ $facture->client->code_client }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                {{ number_format($facture->montants, 2, ',', ' ') }} MAD</td>
                            <td class="px-6 py-4">
                                @php
                                    $bgColor = 'bg-gray-100';
                                    $textColor = 'text-gray-800';
                                    if ($facture->statut == 'Payée') {
                                        $bgColor = 'bg-green-100';
                                        $textColor = 'text-green-800';
                                    } elseif ($facture->statut == 'En attente') {
                                        $bgColor = 'bg-orange-100';
                                        $textColor = 'text-orange-800';
                                    } elseif ($facture->statut == 'En retard') {
                                        $bgColor = 'bg-red-100';
                                        $textColor = 'text-red-800';
                                    }
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bgColor }} {{ $textColor }}">{{ $facture->statut }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $facture->semestre }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <a href="{{ route('factures.show', $facture) }}"
                                    class="text-gray-400 hover:text-blue-600 p-2"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('factures.edit', $facture) }}"
                                    class="text-gray-400 hover:text-indigo-600 p-2"><i class="fas fa-pencil-alt"></i></a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Aucune facture trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filtersForm = document.getElementById('filters-form');

            // On cible les dropdowns
            const selectInputs = document.querySelectorAll('.filter-input');
            selectInputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    filtersForm.submit();
                });
            });

            // On cible la barre de recherche
            const searchInput = document.querySelector('.filter-input-search');
            // On écoute l'événement 'change' (quand on clique en dehors ou appuie sur Entrée)
            searchInput.addEventListener('change', function() {
                filtersForm.submit();
            });
        });
    </script>
@endpush

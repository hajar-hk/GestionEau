@extends('layouts.app')
@section('title', 'Modifier / Annuler un RG8')
@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center"><i
                class="fas fa-edit mr-3 text-orange-500"></i>Modifier / Annuler un RG8</h1>
        <p class="text-gray-500">Modifiez ou annulez les enregistrements de paiement existants</p>
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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Total RG8</p>
            <p class="text-2xl font-bold">{{ $totalRG8 }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Actifs</p>
            <p class="text-2xl font-bold text-green-500">{{ $actifs }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Modifiés</p>
            <p class="text-2xl font-bold text-orange-500">{{ $modifies }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Annulés</p>
            <p class="text-2xl font-bold text-red-500">{{ $annules }}</p>
        </div>
    </div>



    {{-- Section principale --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
        <div class="p-6 bg-white">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Rechercher un RG8</h2>
            {{-- (Filtres à développer plus tard) --}}
            <hr class="my-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Enregistrements RG8</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RG8</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créé par</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($paiements as $paiement)
                            <tr>
                                <td class="px-6 py-4 font-mono text-sm">{{ $paiement->numero_rg8 }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $paiement->facture->client->nom_client }}
                                        {{ $paiement->facture->client->prenom_client }}</div>
                                    <div class="text-xs text-gray-500">{{ $paiement->facture->client->code_client }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold">{{ number_format($paiement->montant_paye, 2, ',', ' ') }}
                                        MAD</div>
                                    @if ($paiement->penalite_retard > 0)
                                        <div class="text-xs text-red-500">
                                            +{{ number_format($paiement->penalite_retard, 2, ',', ' ') }} MAD pénalité
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'Actif' => 'bg-green-100 text-green-800',
                                            'Modifié' => 'bg-orange-100 text-orange-800',
                                            'Annulé' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusStyles[$paiement->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $paiement->statut }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $paiement->user->identifiant_connexion ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                    <a href="{{ route('paiements.show', $paiement) }}"
                                        class="text-gray-400 hover:text-blue-600 p-2"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('paiements.edit', $paiement) }}"
                                        class="text-gray-400 hover:text-indigo-600 p-2"><i
                                            class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('paiements.destroy', $paiement) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ? Cette action est irréversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-2">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">Aucun paiement trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

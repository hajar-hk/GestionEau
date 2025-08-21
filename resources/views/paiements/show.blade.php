@extends('layouts.app')

@section('title', 'Détails du Paiement RG8')

@section('content')

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('paiements.print', $paiement) }}" target="_blank"
            class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-800">
            <i class="fas fa-print mr-2"></i>Imprimer le Reçu
        </a>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            {{-- Header de la page --}}
            <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Détails du Paiement RG8</h1>
                    <p class="text-gray-500">Paiement N° <span
                            class="font-mono font-semibold text-orange-600">{{ $paiement->numero_rg8 }}</span></p>
                </div>
                <a href="{{ route('paiements.index') }}"
                    class="mt-4 sm:mt-0 text-blue-500 hover:text-blue-700 font-semibold flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>

            {{-- Contenu des détails --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- Détails du Paiement --}}
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations sur le Paiement</h3>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Numéro de Reçu</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $paiement->numero_recu }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    <p class="text-lg font-semibold">{{ $paiement->statut }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de Paiement</p>
                    <p class="text-lg text-gray-800">
                        {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Méthode de Règlement</p>
                    <p class="text-lg text-gray-800">{{ $paiement->methode_reglement }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Montant Payé</p>
                    <p class="text-lg text-gray-800">{{ number_format($paiement->montant_paye, 2, ',', ' ') }} MAD</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Pénalité de retard</p>
                    <p class="text-lg text-gray-800">{{ number_format($paiement->penalite_retard, 2, ',', ' ') }} MAD</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Montant Total Versé</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ number_format($paiement->montant_paye + $paiement->penalite_retard, 2, ',', ' ') }} MAD</p>
                </div>

                {{-- Informations sur la Facture et le Client --}}
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Facture et Client Associés</h3>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Numéro de Facture</p>
                    <p class="text-lg text-gray-800 font-mono">{{ $paiement->facture->numero_facture }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Client</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $paiement->facture->client->nom_client }}
                        {{ $paiement->facture->client->prenom_client }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Enregistré par</p>
                    <p class="text-lg text-gray-800">{{ $paiement->user->nom }} {{ $paiement->user->prenom }}
                        ({{ $paiement->user->role }})</p>
                </div>
            </div>
        </div>
    </div>

@endsection

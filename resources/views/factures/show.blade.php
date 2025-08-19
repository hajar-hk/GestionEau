@extends('layouts.app')

@section('title', 'Détails de la Facture')

@section('content')

    <div class="max-w-4xl mx-auto">

        <div class="bg-white p-6 rounded-xl shadow-sm">
            {{-- Header de la page --}}
            <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Détails de la Facture</h1>
                    <p class="text-gray-500">Facture N° <span
                            class="font-mono font-semibold text-purple-600">{{ $facture->numero_facture }}</span></p>
                </div>
                <a href="{{ route('factures.index') }}"
                    class="mt-4 sm:mt-0 text-blue-500 hover:text-blue-700 font-semibold flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>

            {{-- Contenu des détails --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- Informations sur le Client --}}
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations sur le Client</h3>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Nom Complet</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $facture->client->nom_client }}
                        {{ $facture->client->prenom_client }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Code Client</p>
                    <p class="text-lg text-gray-800 font-mono">{{ $facture->client->code_client }}</p>
                </div>

                {{-- Détails de la Facture --}}
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Détails de la Facture</h3>
                </div>


                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Montant</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($facture->montants, 2, ',', ' ') }}
                        MAD</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Semestre</p>
                    <p class="text-lg text-gray-800">{{ $facture->semestre }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    <p
                        class="text-lg font-semibold {{ $facture->statut == 'Payée' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ $facture->statut }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Date d'émission</p>
                    <p class="text-lg text-gray-800">{{ \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Date d'échéance</p>
                    <p class="text-lg text-gray-800">{{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection

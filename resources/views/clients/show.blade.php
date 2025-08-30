@extends('layouts.app')

@section('title', 'Détails du Client')

@section('content')

    <div class="max-w-4xl mx-auto">

        <div class="bg-white p-6 rounded-xl shadow-sm">
            {{-- Header de la page --}}
            <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Détails du Client</h1>
                    <p class="text-gray-500">Informations complètes sur le client <span
                            class="font-mono font-semibold text-blue-600">{{ $client->code_client }}</span></p>
                </div>
                <a href="{{ route('clients.index') }}"
                    class="mt-4 sm:mt-0 text-blue-500 hover:text-blue-700 font-semibold flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>

            {{-- Contenu des détails --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- Information Personnelle --}}
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations Personnelles</h3>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Nom Complet</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $client->nom_client }} {{ $client->prenom_client }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Code Client</p>
                    <p class="text-lg text-gray-800 font-mono">{{ $client->code_client }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="text-lg text-gray-800">{{ $client->email }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Téléphone</p>
                    <p class="text-lg text-gray-800">{{ $client->telephone ?? 'N/A' }}</p>
                </div>

                {{-- Information de Gestion --}}
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations de Gestion</h3>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Secteur</p>
                    <p class="text-lg text-gray-800">{{ $client->secteur }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    @if ($client->statut === 'Actif')
                        <p class="text-lg font-semibold text-green-600">Actif</p>
                    @else
                        <p class="text-lg font-semibold text-red-600">Inactif</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Date d'inscription</p>
                    <p class="text-lg text-gray-800">{{ $client->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Dernière modification</p>
                    <p class="text-lg text-gray-800">{{ $client->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Hna men be3d nqdrou nzido la liste dyal les factures dyal had l'client a voir maneba3d!! --}}

    </div>

@endsection

@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
    {{-- Salutation --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
        <p class="text-gray-500">Bonjour {{ Auth::user()->prenom }}, voici un aperçu de vos activités récentes.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Paiements du mois</p>
            <p class="text-3xl font-bold">{{ $paiementsDuMois }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Factures en attente</p>
            <p class="text-3xl font-bold text-orange-500">{{ $facturesEnAttente }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Clients actifs</p>
            <p class="text-3xl font-bold text-blue-500">{{ $clientsActifs }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Recouvrement mensuel</p>
            <p class="text-3xl font-bold text-green-500">{{ number_format($recouvrementMensuel, 2, ',', ' ') }} <span
                    class="text-lg">MAD</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Colonne de gauche: Actions Rapides --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Actions rapides</h2>
                <div class="space-y-3">
                    <a href="{{ route('rg8.create') }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg">
                        <p class="font-semibold text-gray-700">Enregistrer un paiement</p>
                        <p class="text-sm text-gray-500">Créer un nouveau RG8</p>
                    </a>
                    <a href="{{ route('factures.index') }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg">
                        <p class="font-semibold text-gray-700">Gérer les factures</p>
                        <p class="text-sm text-gray-500">Consulter et modifier les factures</p>
                    </a>
                    <a href="{{ route('clients.index') }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg">
                        <p class="font-semibold text-gray-700">Gérer les clients</p>
                        <p class="text-sm text-gray-500">Ajouter ou modifier les clients</p>
                    </a>
                </div>
            </div>
        </div>

        {{-- Colonne de droite: Activités Récentes --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Activités récentes</h2>
                <div class="space-y-4">
                    {{-- On va laisser cette partie statique pour le moment --}}
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center"><i
                                class="fas fa-check text-green-500"></i></div>
                        <div class="ml-4">
                            <p>Paiement enregistré</p>
                            <p class="text-sm text-gray-500">Il y a 5 minutes</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center"><i
                                class="fas fa-file-invoice text-blue-500"></i></div>
                        <div class="ml-4">
                            <p>Facture générée</p>
                            <p class="text-sm text-gray-500">Il y a 15 minutes</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center"><i
                                class="fas fa-user-plus text-green-500"></i></div>
                        <div class="ml-4">
                            <p>Client ajouté</p>
                            <p class="text-sm text-gray-500">Il y a 1 heure</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center"><i
                                class="fas fa-exclamation-triangle text-orange-500"></i></div>
                        <div class="ml-4">
                            <p>Retard de paiement</p>
                            <p class="text-sm text-gray-500">Il y a 2 heures</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

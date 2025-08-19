@extends('layouts.app')
@section('title', 'Tableau de Bord - Admin')
@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
        <p class="text-gray-500">Bonjour {{ Auth::user()->prenom }}, voici un aperçu de l'activité globale du système.</p>
    </div>

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
            <p class="text-sm text-gray-500">Créances en cours</p>
            <p class="text-3xl font-bold text-orange-500">{{ number_format($creancesEnCours, 2, ',', ' ') }} <span
                    class="text-lg">MAD</span></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Retards de paiement</p>
            <p class="text-3xl font-bold text-red-500">{{ $retardsDePaiement }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Actions rapides</h2>
                <div class="space-y-3">
                    <a href="{{ route('users.index') }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg">
                        <p class="font-semibold">Gérer les régisseurs</p>
                        <p class="text-sm text-gray-500">Ajouter ou modifier les régisseurs</p>
                    </a>
                    <a href="#" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg">
                        <p class="font-semibold">Consulter statistiques</p>
                        <p class="text-sm text-gray-500">Voir les rapports détaillés</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Activités récentes</h2>
                <p class="text-center text-gray-400 py-8">Section en construction</p>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'Déclaration de Paiement')
@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center"><i
                class="fas fa-chart-bar mr-3 text-indigo-500"></i>Déclaration de Paiement</h1>
        <p class="text-gray-500">Générez et gérez les déclarations de paiement officielles</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Succès</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Erreurs</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Colonne de gauche --}}
        {{-- Colonne de gauche (Formulaire et Infos) --}}
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold mb-4">Nouvelle Déclaration</h2>

                <form id="declaration-form" action="{{ route('declarations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div><label>Numéro de Déclaration *</label><input type="text" name="numero_declaration"
                            class="w-full mt-1 p-2 border rounded-md"></div>
                    <div><label>Période *</label><input type="text" name="periode" placeholder="Ex: Janvier 2024"
                            class="w-full mt-1 p-2 border rounded-md"></div>
                    <div><label>Date de Déclaration *</label><input type="date" name="date_declaration"
                            value="{{ date('Y-m-d') }}" class="w-full mt-1 p-2 border rounded-md"></div>

                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold">Résumé de la sélection</h3>
                        <div class="flex justify-between mt-2"><span>Bordereaux:</span><span id="recap-count"
                                class="font-bold">0</span></div>
                        <div class="flex justify-between mt-1"><span>Montant total:</span><span id="recap-total"
                                class="font-bold text-lg text-green-600">0.00 MAD</span></div>
                    </div>

                    <button type="submit"
                        class="w-full mt-4 bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600">Générer la
                        Déclaration</button>
                </form>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold mb-4">Informations</h2>
                <div class="bg-green-50 border-l-4 border-green-400 p-3 rounded-r-lg mb-3">
                    <h4 class="font-semibold text-green-800">Délais réglementaires</h4>
                    <p class="text-sm text-green-700">Les déclarations doivent être soumises avant le 10 du mois suivant.
                    </p>
                </div>
                <div class="bg-orange-50 border-l-4 border-orange-400 p-3 rounded-r-lg">
                    <h4 class="font-semibold text-orange-800">Format requis</h4>
                    <p class="text-sm text-orange-700">Les montants doivent être déclarés en dirhams marocains (MAD).</p>
                </div>
            </div>
        </div>

        {{-- Colonne de droite --}}
        {{-- Colonne de droite (MAINTENANT AVEC SÉLECTION) --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold mb-4">Bordereaux RG12 à inclure</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b">
                            <tr>
                                <th class="w-10 p-2"><input type="checkbox" id="select-all"></th>
                                <th class="p-2 text-left">RG12</th>
                                <th class="p-2 text-left">Montant</th>
                                <th class="p-2 text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bordereauxDisponibles as $bordereau)
                                <tr>
                                    <td class="p-2"><input type="checkbox" form="declaration-form" name="bordereau_ids[]"
                                            value="{{ $bordereau->id }}" class="item-checkbox"
                                            data-montant="{{ $bordereau->montant_total }}"></td>
                                    <td class="p-2 font-mono">{{ $bordereau->numero_rg12 }}</td>
                                    <td class="p-2 font-semibold">{{ number_format($bordereau->montant_total, 2) }} MAD</td>
                                    <td class="p-2">
                                        {{ \Carbon\Carbon::parse($bordereau->date_creation)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-500">Aucun bordereau RG12
                                        disponible.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold mb-4">Résumé Mensuel</h2>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-3xl font-bold">{{ $declarationsSoumises }}</p>
                        <p class="text-sm text-gray-500">Déclarations soumises</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-green-600">
                            {{ number_format($totalDeclare / 1000000, 1, ',', '') }}M</p>
                        <p class="text-sm text-gray-500">Total déclaré (MAD)</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-blue-600">{{ $tauxValidation }}%</p>
                        <p class="text-sm text-gray-500">Taux de validation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- On ajoute le même JS que RG12 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selectAllCheckbox = document.getElementById('select-all');
            const recapCount = document.getElementById('recap-count');
            const recapTotal = document.getElementById('recap-total');

            function updateRecap() {
                let count = 0;
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        count++;
                        total += parseFloat(cb.dataset.montant);
                    }
                });
                recapCount.textContent = count;
                recapTotal.textContent = total.toFixed(2) + ' MAD';
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateRecap));
            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateRecap();
            });
        });
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Génération RG12')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center"><i
                class="fas fa-file-invoice mr-3 text-teal-500"></i>Génération RG12</h1>
        <p class="text-gray-500">Créez un bordereau RG12 à partir de plusieurs enregistrements RG8</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Erreurs :</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Succès</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <form action="{{ route('rg12.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Colonne de gauche --}}
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Nouveau RG12</h2>
                    <div><label for="numero_rg12" class="text-sm font-medium text-gray-700">Numéro RG12</label><input
                            type="text" name="numero_rg12" id="numero_rg12"
                            class="w-full mt-1 border-gray-300 rounded-md p-2"></div>
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700">Résumé de la sélection</h3>
                        <div class="flex justify-between mt-2 text-sm"><span>Enregistrements sélectionnés :</span><span
                                id="recap-count" class="font-bold">0</span></div>
                        <div class="flex justify-between mt-1 text-sm"><span>Montant total :</span><span id="recap-total"
                                class="font-bold text-lg text-green-600">0.00 MAD</span></div>
                    </div>
                    <button type="submit"
                        class="w-full mt-4 bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 flex items-center justify-center"><i
                            class="fas fa-download mr-2"></i>Générer RG12</button>
                </div>

                {{-- RG12 Récents --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">RG12 récents</h2>
                    <div class="space-y-3 max-h-60 overflow-y-auto">
                        @forelse ($rg12Recents as $rg12)
                            <div class="border p-3 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $rg12->numero_rg12 }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($rg12->date_creation)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">
                                        {{ number_format($rg12->montant_total, 2, ',', ' ') }} MAD</p>

                                    <a href="{{ route('rg12.print', $rg12) }}" target="_blank"
                                        class="text-gray-400 hover:text-gray-800">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">Historique indisponible pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Colonne de droite --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Enregistrements RG8 disponibles</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b">
                            <tr class="text-xs font-medium text-gray-500 uppercase">
                                <th class="p-2 text-left w-10"><input type="checkbox" id="select-all"></th>
                                <th class="p-2 text-left">RG8</th>
                                <th class="p-2 text-left">Client</th>
                                <th class="p-2 text-left">Montant</th>
                                <th class="p-2 text-left">Pénalité</th>
                                <th class="p-2 text-left">Total</th>
                                <th class="p-2 text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paiements as $paiement)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2"><input type="checkbox" name="paiement_ids[]"
                                            value="{{ $paiement->id }}" class="paiement-checkbox"
                                            data-total="{{ $paiement->montant_paye + $paiement->penalite_retard }}"></td>
                                    <td class="p-2 font-mono text-sm">{{ $paiement->numero_rg8 }}</td>
                                    <td class="p-2">
                                        <div class="font-medium">{{ $paiement->facture->client->nom_client }}</div>
                                        <div class="text-xs text-gray-500">{{ $paiement->facture->client->code_client }}
                                        </div>
                                    </td>
                                    <td class="p-2">{{ number_format($paiement->montant_paye, 2) }}</td>
                                    <td class="p-2 text-red-500">{{ number_format($paiement->penalite_retard, 2) }}</td>
                                    <td class="p-2 font-semibold">
                                        {{ number_format($paiement->montant_paye + $paiement->penalite_retard, 2) }}</td>
                                    <td class="p-2 text-sm">
                                        {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-gray-500">Aucun enregistrement RG8
                                        disponible à inclure.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.paiement-checkbox');
            const selectAllCheckbox = document.getElementById('select-all');
            const recapCount = document.getElementById('recap-count');
            const recapTotal = document.getElementById('recap-total');

            function updateRecap() {
                let count = 0;
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        count++;
                        total += parseFloat(cb.dataset.total);
                    }
                });
                recapCount.textContent = count;
                recapTotal.textContent = total.toFixed(2) + ' MAD';
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateRecap));

            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateRecap();
            });
        });
    </script>
@endpush

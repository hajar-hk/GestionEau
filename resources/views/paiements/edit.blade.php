@extends('layouts.app')

@section('title', 'Modifier le Paiement RG8')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800">Modifier le Paiement RG8</h1>
                <a href="{{ route('paiements.index') }}" class="text-blue-500 hover:text-blue-700 font-semibold">Retour</a>
            </div>

            <form action="{{ route('paiements.update', $paiement) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="numero_recu" class="block text-sm font-medium text-gray-700">Numéro de Reçu *</label>
                        <input type="text" name="numero_recu" id="numero_recu"
                            value="{{ old('numero_recu', $paiement->numero_recu) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('numero_recu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="methode_reglement" class="block text-sm font-medium text-gray-700">Méthode de Règlement
                            *</label>
                        <select name="methode_reglement" id="methode_reglement"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                            @php $methods = ['Espèce', 'Chèque', 'Virement', 'Versement']; @endphp
                            @foreach ($methods as $method)
                                <option value="{{ $method }}" @if (old('methode_reglement', $paiement->methode_reglement) == $method) selected @endif>
                                    {{ $method }}</option>
                            @endforeach
                        </select>
                        @error('methode_reglement')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="montant_paye" class="block text-sm font-medium text-gray-700">Montant Payé (MAD)
                            *</label>
                        <input type="number" step="0.01" name="montant_paye" id="montant_paye"
                            value="{{ old('montant_paye', $paiement->montant_paye) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('montant_paye')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="penalite_retard" class="block text-sm font-medium text-gray-700">Pénalité (MAD)
                            *</label>
                        <input type="number" step="0.01" name="penalite_retard" id="penalite_retard"
                            value="{{ old('penalite_retard', $paiement->penalite_retard) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('penalite_retard')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_paiement" class="block text-sm font-medium text-gray-700">Date de Paiement
                            *</label>
                        <input type="datetime-local" name="date_paiement" id="date_paiement"
                            value="{{ old('date_paiement', \Carbon\Carbon::parse($paiement->date_paiement)->format('Y-m-d\TH:i')) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('date_paiement')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut *</label>
                        <select name="statut" id="statut" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="Actif" @if (old('statut', $paiement->statut) == 'Actif') selected @endif>Actif</option>
                            <option value="Modifié" @if (old('statut', $paiement->statut) == 'Modifié') selected @endif>Modifié</option>
                        </select>
                        @error('statut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

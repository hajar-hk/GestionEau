@extends('layouts.app')

@section('title', 'Modifier la Facture')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800">Modifier la Facture</h1>
                <a href="{{ route('factures.index') }}"
                    class="text-blue-500 hover:text-blue-700 font-semibold flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>

            <form action="{{ route('factures.update', $facture) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="numero_facture" class="block text-sm font-medium text-gray-700">Numéro de Facture
                            *</label>
                        <input type="text" name="numero_facture" id="numero_facture"
                            value="{{ old('numero_facture', $facture->numero_facture) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('numero_facture')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Client *</label>
                        <select name="client_id" id="client_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @if (old('client_id', $facture->client_id) == $client->id) selected @endif>
                                    {{ $client->nom_client }} {{ $client->prenom_client }} ({{ $client->code_client }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="montants" class="block text-sm font-medium text-gray-700">montant (MAD) *</label>
                        <input type="number" step="0.01" name="montants" id="montants"
                            value="{{ old('montants', $facture->montants) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('montants')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="date_emission" class="block text-sm font-medium text-gray-700">Date d'émission *</label>
                        <input type="date" name="date_emission" id="date_emission"
                            value="{{ old('date_emission', $facture->date_emission) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('date_emission')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_echeance" class="block text-sm font-medium text-gray-700">Date d'échéance *</label>
                        <input type="date" name="date_echeance" id="date_echeance"
                            value="{{ old('date_echeance', $facture->date_echeance) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('date_echeance')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semestre" class="block text-sm font-medium text-gray-700">Semestre *</label>
                        <input type="text" name="semestre" id="semestre"
                            value="{{ old('semestre', $facture->semestre) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('semestre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut *</label>
                        <select name="statut" id="statut" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="En attente" @if (old('statut', $facture->statut) == 'En attente') selected @endif>En attente</option>
                            <option value="Payée" @if (old('statut', $facture->statut) == 'Payée') selected @endif>Payée</option>
                            <option value="En retard" @if (old('statut', $facture->statut) == 'En retard') selected @endif>En retard</option>
                            <option value="Annulée" @if (old('statut', $facture->statut) == 'Annulée') selected @endif>Annulée</option>
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

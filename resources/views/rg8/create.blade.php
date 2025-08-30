@extends('layouts.app')

@section('title', 'Saisie RG8')

@section('content')

    {{-- ########## BLOC POUR AFFICHER LES MESSAGES ########## --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">Erreur!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-credit-card mr-3 text-purple-500"></i>
            Gestion des paiements d'irrigation
        </h1>
        <p class="text-gray-500">Enregistrez un nouveau paiement RG8 pour un client</p>
    </div>

    <form id="rg8-form" action="{{ url('/rg8') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Colonne de gauche: Informations --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Informations Client</h2>

                <div class="relative mb-4">
                    <input type="text" id="search-client-input" placeholder="Rechercher par code client..."
                        class="w-full p-2 border rounded-md">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="hidden" id="client-id" name="client_id">

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Code Client</label>
                        <input type="text" id="client-code" class="w-full p-2 bg-gray-100 border-gray-200 rounded-md"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nom et Prénom</label>
                        <input type="text" id="client-name" class="w-full p-2 bg-gray-100 border-gray-200 rounded-md"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Téléphone</label>
                        <input type="text" id="client-phone" class="w-full p-2 bg-gray-100 border-gray-200 rounded-md"
                            readonly>
                    </div>

                    <div>
                        <label for="date-operation" class="block text-sm font-medium text-gray-600">Date</label>
                        <input type="date" id="date-operation" name="date_operation" value="{{ date('Y-m-d') }}"
                            class="w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="secteur" class="block text-sm font-medium text-gray-600">Secteur</label>
                        <input type="text" id="secteur" name="secteur"
                            class="w-full p-2 bg-gray-100 border-gray-200 rounded-md" readonly>
                    </div>

                    <div>
                        <label for="numero-recu" class="block text-sm font-medium text-gray-600">N° Reçu</label>
                        <input type="text" id="numero-recu" name="numero_recu"
                            class="w-full p-2 border border-gray-300 rounded-md" placeholder="Entrez le n° du reçu...">
                    </div>

                    {{-- Numéro RG8 --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Numéro RG8</label>
                        <div class="w-full p-2 bg-gray-100 border-gray-200 rounded-md text-gray-500 italic">
                            Sera généré automatiquement lors de l'enregistrement
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Méthode de Règlement</label>
                        <div class="flex flex-wrap gap-x-6 gap-y-2">
                            <div class="flex items-center">
                                <input id="reg-espece" value="espece" name="methode_reglement" type="radio"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" checked>
                                <label for="reg-espece" class="ml-2 block text-sm text-gray-900">Espèce</label>
                            </div>
                            <div class="flex items-center">
                                <input id="reg-cheque" value="cheque" name="methode_reglement" type="radio"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="reg-cheque" class="ml-2 block text-sm text-gray-900">Chèque</label>
                            </div>
                            <div class="flex items-center">
                                <input id="reg-virement" value="virement" name="methode_reglement" type="radio"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="reg-virement" class="ml-2 block text-sm text-gray-900">Virement</label>
                            </div>
                            <div class="flex items-center">
                                <input id="reg-versement" value="versement" name="methode_reglement" type="radio"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="reg-versement" class="ml-2 block text-sm text-gray-900">Versement</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne de droite: Factures Disponibles --}}
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Factures Disponibles</h2>
                <div id="factures-list" class="space-y-3 max-h-96 overflow-y-auto">
                    <p class="text-gray-500">Veuillez rechercher un client pour voir ses factures.</p>
                </div>
            </div>

        </div>

        {{-- Section Récapitulatif --}}
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Récapitulatif RG8</h2>

            <div id="recap-list-container">
                <div class="text-center py-8">
                    <p id="recap-empty-message" class="text-gray-500">Aucune facture ajoutée au RG8</p>
                </div>
                <table id="recap-table" class="min-w-full divide-y divide-gray-200 hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N°
                                Facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody id="recap-table-body" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="mt-4 pt-4 border-t flex justify-end items-center">
                <span class="text-lg font-semibold text-gray-700">Total à Payer:</span>
                <span id="recap-total" class="text-xl font-bold text-green-600 ml-4">0.00 MAD</span>
            </div>

            <div class="flex justify-end mt-6">
                <button id="submit-payment-btn" type="submit"
                    class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-green-700">
                    Enregistrer le Paiement
                </button>
            </div>
        </div>

    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === DOM ELEMENTS ===
            const searchInput = document.getElementById('search-client-input');

            // Empêcher la touche "Entrée" sur le champ de recherche de soumettre le formulaire
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Annuler l'action par défaut (soumission)
                    // Déclencher manuellement l'événement 'change' pour lancer la recherche
                    searchInput.dispatchEvent(new Event('change'));
                }
            });
            const facturesListContainer = document.getElementById('factures-list');
            const recapTable = document.getElementById('recap-table');
            const recapTableBody = document.getElementById('recap-table-body');
            const recapEmptyMessage = document.getElementById('recap-empty-message');
            const recapTotalSpan = document.getElementById('recap-total');
            const rg8Form = document.getElementById('rg8-form');
            const submitBtn = document.getElementById('submit-payment-btn');


            // === STATE ===
            let selectedFactures = [];

            // === FUNCTIONS ===

            rg8Form.addEventListener('submit', function() {
                // On désactive le bouton pour éviter les double-clics
                submitBtn.disabled = true;
                // On change le texte pour montrer que ça charge
                submitBtn.textContent = 'Enregistrement en cours...';
            });

            function updateRecap() {
                recapTableBody.innerHTML = '';
                let total = 0;

                if (selectedFactures.length > 0) {
                    recapTable.classList.remove('hidden');
                    recapEmptyMessage.style.display = 'none';

                    selectedFactures.forEach((facture, index) => {
                        const row = `
                            <tr id="recap-row-${facture.id}">
                                <td class="px-6 py-4 whitespace-nowrap">${facture.numero_facture}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${parseFloat(facture.montants_ttc).toFixed(2)} MAD</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <button type="button" data-index="${index}" class="remove-facture-btn text-red-500 hover:text-red-700">Supprimer</button>
                                </td>
                            </tr>
                        `;
                        recapTableBody.innerHTML += row;
                        total += parseFloat(facture.montants_ttc);
                    });
                } else {
                    recapTable.classList.add('hidden');
                    recapEmptyMessage.style.display = 'block';
                }

                recapTotalSpan.textContent = total.toFixed(2) + ' MAD';
            }

            function resetForm() {
                document.getElementById('client-id').value = '';
                document.getElementById('client-code').value = '';
                document.getElementById('client-name').value = '';
                document.getElementById('client-phone').value = '';
                document.getElementById('secteur').value = '';
                facturesListContainer.innerHTML =
                    '<p class="text-gray-500">Veuillez rechercher un client pour voir ses factures.</p>';
                selectedFactures = [];
                updateRecap();
            }

            // === EVENT LISTENERS ===
            facturesListContainer.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('add-facture-btn')) {
                    const button = event.target;
                    const factureId = button.dataset.id;

                    if (!selectedFactures.some(f => f.id == factureId)) {
                        selectedFactures.push({
                            id: factureId,
                            numero_facture: button.dataset.numero,
                            montants_ttc: button.dataset.montant
                        });
                        button.textContent = '✓';
                        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                        button.classList.add('bg-green-500');
                        updateRecap();
                    } else {
                        alert('Cette facture est déjà ajoutée.');
                    }
                }
            });

            recapTableBody.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-facture-btn')) {
                    const indexToRemove = event.target.dataset.index;
                    const removedFactureId = selectedFactures[indexToRemove].id;

                    selectedFactures.splice(indexToRemove, 1);
                    updateRecap();

                    const addButton = facturesListContainer.querySelector(
                        `.add-facture-btn[data-id='${removedFactureId}']`);
                    if (addButton) {
                        addButton.textContent = '+';
                        addButton.classList.remove('bg-green-500');
                        addButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
                    }
                }
            });

            searchInput.addEventListener('change', function() {
                const query = this.value;
                resetForm();
                searchInput.value = query;

                if (query.length > 0) {
                    const url = `{{ route('clients.search') }}?query=${query}`;
                    fetch(url)
                        .then(response => {
                            if (!response.ok) throw new Error('Client non trouvé');
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById('client-id').value = data.client.id;
                            document.getElementById('client-code').value = data.client.code_client;
                            document.getElementById('client-name').value = data.client.nom_client +
                                ' ' + data.client.prenom_client;
                            document.getElementById('client-phone').value = data.client.telephone;
                            document.getElementById('secteur').value = data.client.secteur;

                            facturesListContainer.innerHTML = '';
                            if (data.factures.length > 0) {
                                data.factures.forEach(facture => {
                                    facturesListContainer.innerHTML += `
                                        <div class="border p-4 rounded-lg flex justify-between items-center">
                                            <div>
                                                <p class="font-bold">${facture.numero_facture}</p>
                                                <p class="text-sm text-green-600 font-semibold">${parseFloat(facture.montants).toFixed(2)} MAD</p>
                                            </div>
                                            <button type="button" 
                                                    class="add-facture-btn bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-blue-600"
                                                    data-id="${facture.id}"
                                                    data-numero="${facture.numero_facture}"
                                                    data-montant="${facture.montants}">+</button>
                                        </div>
                                    `;
                                });
                            } else {
                                facturesListContainer.innerHTML =
                                    '<p class="text-gray-500">Ce client n\'a aucune facture non payée.</p>';
                            }
                        })
                        .catch(error => {
                            alert(error.message);
                        });
                }
            });

            rg8Form.addEventListener('submit', function(event) {
                if (selectedFactures.length === 0) {
                    event.preventDefault(); // Empêcher l'envoi du formulaire
                    alert('Veuillez ajouter au moins une facture au récapitulatif avant d\'enregistrer.');
                    return;
                }

                // Ajouter les factures sélectionnées comme champs cachés
                selectedFactures.forEach(facture => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'factures_ids[]'; // '[]' pour envoyer un tableau
                    hiddenInput.value = facture.id;
                    rg8Form.appendChild(hiddenInput);
                });
            });
        });
    </script>
@endpush

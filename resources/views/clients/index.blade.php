@extends('layouts.app')

@section('title', 'Gestion des Clients')

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center"><i
                class="fas fa-user-friends mr-3 text-blue-500"></i>Gestion des Clients</h1>
        <p class="text-gray-500">Gérez les informations de vos clients d'irrigation</p>
    </div>

    {{-- Messages de session --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Succès</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Erreur</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Total clients</p>
            <p class="text-2xl font-bold">{{ $totalClients }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Clients actifs</p>
            <p class="text-2xl font-bold text-green-500">{{ $clientsActifs }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Clients inactifs</p>
            <p class="text-2xl font-bold text-red-500">{{ $clientsInactifs }}</p>
        </div>
    </div>

    {{-- Clients Table Section --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
        <div class="p-6 bg-white">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Liste des clients</h2>
                    <p class="text-sm text-gray-500">Gérez vos clients et leurs informations</p>
                </div>
                <button id="add-client-btn"
                    class="w-full sm:w-auto bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un client
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom complet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Secteur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($clients as $client)
                            <tr>
                                <td class="px-6 py-4 font-mono text-sm text-gray-700">{{ $client->code_client }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $client->nom_client }}
                                        {{ $client->prenom_client }}</div>
                                    <div class="text-xs text-gray-500">Inscrit le {{ $client->created_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $client->secteur }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $client->telephone }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $client->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($client->statut === 'Actif')
                                        <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>@else<span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactif</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                    <a href="{{ route('clients.show', $client) }}"
                                        class="text-gray-400 hover:text-blue-600"><i class="fas fa-eye"></i></a>
                                    <button type="button" class="edit-client-btn text-gray-400 hover:text-indigo-600"
                                        data-id="{{ $client->id }}"><i class="fas fa-pencil-alt"></i></button>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Aucun client trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- MODAL --}}
    <div id="client-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modal-title" class="text-xl leading-6 font-bold text-gray-900"></h3>
                    <button id="close-modal-btn" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form id="client-form" action="" method="POST">
                    @csrf
                    <div id="form-method"></div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-4">
                        <div><label for="code_client" class="text-sm font-medium">Code *</label><input type="text"
                                name="code_client" id="code_client" class="w-full mt-1 border-gray-300 rounded-md"></div>
                        <div><label for="nom_client" class="text-sm font-medium">Nom *</label><input type="text"
                                name="nom_client" id="nom_client" class="w-full mt-1 border-gray-300 rounded-md"></div>
                        <div><label for="prenom_client" class="text-sm font-medium">Prénom *</label><input type="text"
                                name="prenom_client" id="prenom_client" class="w-full mt-1 border-gray-300 rounded-md">
                        </div>
                        <div><label for="telephone" class="text-sm font-medium">Téléphone</label><input type="text"
                                name="telephone" id="telephone" class="w-full mt-1 border-gray-300 rounded-md"></div>
                        <div class="sm:col-span-2"><label for="email" class="text-sm font-medium">Email
                                *</label><input type="email" name="email" id="email"
                                class="w-full mt-1 border-gray-300 rounded-md"></div>
                        <div><label for="secteur" class="text-sm font-medium">Secteur</label><select name="secteur"
                                id="secteur" class="w-full mt-1 border-gray-300 rounded-md">
                                <option>Nord</option>
                                <option>Sud</option>
                                <option>Est</option>
                                <option>Ouest</option>
                                <option>Centre</option>
                            </select></div>
                        <div><label for="statut" class="text-sm font-medium">Statut</label><select name="statut"
                                id="statut" class="w-full mt-1 border-gray-300 rounded-md">
                                <option>Actif</option>
                                <option>Inactif</option>
                            </select></div>
                    </div>
                    <div class="flex justify-end gap-4 mt-6 border-t pt-4">
                        <button id="cancel-btn" type="button" class="px-4 py-2 bg-gray-200 rounded-md">Annuler</button>
                        <button id="submit-btn" type="submit"
                            class="px-4 py-2 bg-green-500 text-white rounded-md"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clientModal = document.getElementById('client-modal');
            const modalTitle = document.getElementById('modal-title');
            const clientForm = document.getElementById('client-form');
            const formMethodDiv = document.getElementById('form-method');
            const submitBtn = document.getElementById('submit-btn');
            const addClientBtn = document.getElementById('add-client-btn');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const tableBody = document.querySelector('tbody');

            function setupCreateModal() {
                clientForm.action = "{{ route('clients.store') }}";
                formMethodDiv.innerHTML = "";
                modalTitle.textContent = "Ajouter un nouveau client";
                submitBtn.textContent = "Créer le client";
                clientForm.reset();
                clientModal.classList.remove('hidden');
            }

            function setupEditModal(data) {
                clientForm.action = `/clients/${data.id}`;
                formMethodDiv.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
                modalTitle.textContent = "Modifier le client";
                submitBtn.textContent = "Enregistrer";

                document.getElementById('code_client').value = data.code_client;
                document.getElementById('nom_client').value = data.nom_client;
                document.getElementById('prenom_client').value = data.prenom_client;
                document.getElementById('telephone').value = data.telephone;
                document.getElementById('email').value = data.email;
                document.getElementById('secteur').value = data.secteur;
                document.getElementById('statut').value = data.statut;

                clientModal.classList.remove('hidden');
            }

            addClientBtn.addEventListener('click', setupCreateModal);

            tableBody.addEventListener('click', function(event) {
                const editBtn = event.target.closest('.edit-client-btn');
                if (editBtn) {
                    const clientId = editBtn.dataset.id;
                    fetch(`/clients/${clientId}/edit`)
                        .then(response => response.json())
                        .then(data => setupEditModal(data));
                }
            });

            closeModalBtn.addEventListener('click', () => clientModal.classList.add('hidden'));
            cancelBtn.addEventListener('click', () => clientModal.classList.add('hidden'));
        });
    </script>
@endpush

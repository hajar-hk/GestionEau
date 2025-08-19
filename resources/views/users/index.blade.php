@extends('layouts.app')

@section('title', 'Gestion des Régisseurs')

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center"><i
                class="fas fa-users-cog mr-3 text-green-500"></i>Gestion des Régisseurs</h1>
        <p class="text-gray-500">Gérez les comptes utilisateurs et leurs permissions</p>
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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Total utilisateurs</p>
            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Comptes actifs</p>
            <p class="text-2xl font-bold">{{ $totalComptesActifs }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Régisseurs</p>
            <p class="text-2xl font-bold text-orange-500">{{ $totalRegisseurs }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Administrateurs</p>
            <p class="text-2xl font-bold text-green-500">{{ $totalAdmins }}</p>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
        <div class="p-6 bg-white">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Liste des régisseurs</h2>
                    <p class="text-sm text-gray-500">Gérez les comptes utilisateurs du système</p>
                </div>
                <button id="add-user-btn"
                    class="w-full sm:w-auto bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Ajouter un régisseur
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $user->nom }} {{ $user->prenom }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->identifiant_connexion }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->role === 'Admin')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Administrateur</span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Régisseur</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="text-gray-400 hover:text-blue-600"><i class="fas fa-eye"></i></a>

                                    <button type="button" class="edit-user-btn text-gray-400 hover:text-indigo-600"
                                        data-id="{{ $user->id }}" data-url="{{ route('users.update', $user->id) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    {{-- Formulaire de Suppression --}}
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucun utilisateur trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div id="user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modal-title" class="text-xl leading-6 font-bold text-gray-900"></h3>
                    <button id="close-modal-btn" class="text-gray-400 hover:text-gray-600"><i
                            class="fas fa-times fa-lg"></i></button>
                </div>

                <form id="user-form" action="" method="POST">
                    @csrf
                    <div id="form-method"></div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-4">
                        <div>
                            <label for="identifiant_connexion" class="block text-sm font-medium text-gray-700">Nom
                                d'utilisateur *</label>
                            <input type="text" name="identifiant_connexion" id="identifiant_connexion"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                            <select id="role" name="role"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option>Régisseur</option>
                                <option>Admin</option>
                            </select>
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom *</label>
                            <input type="text" name="prenom" id="prenom"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom *</label>
                            <input type="text" name="nom" id="nom"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="email" id="email"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <input type="password" name="password" id="password"
                                placeholder="Laissez vide pour ne pas changer"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="items-center px-4 py-3 border-t mt-4">
                        <div class="flex justify-end gap-4">
                            <button id="cancel-btn" type="button"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Annuler</button>
                            <button id="submit-btn" type="submit"
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userModal = document.getElementById('user-modal');
            const modalTitle = document.getElementById('modal-title');
            const userForm = document.getElementById('user-form');
            const formMethodDiv = document.getElementById('form-method');
            const submitBtn = document.getElementById('submit-btn');
            const addUserBtn = document.getElementById('add-user-btn');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const tableBody = document.querySelector('tbody');

            //fct pr création
            function setupCreateModal() {
                userForm.action = "{{ route('users.store') }}";
                formMethodDiv.innerHTML = "";
                modalTitle.textContent = "Ajouter un nouveau régisseur";
                submitBtn.textContent = "Créer le compte";
                userForm.reset();
                document.getElementById('password').placeholder = "Mot de passe initial *";
                userModal.classList.remove('hidden');
            }

            //fct pour mise a jour
            function setupEditModal(data, updateUrl) {
                userForm.action = updateUrl;
                formMethodDiv.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
                modalTitle.textContent = "Modifier l'utilisateur";
                submitBtn.textContent = "Enregistrer les modifications";

                document.getElementById('nom').value = data.nom;
                document.getElementById('prenom').value = data.prenom;
                document.getElementById('email').value = data.email;
                document.getElementById('identifiant_connexion').value = data.identifiant_connexion;
                document.getElementById('role').value = data.role;
                document.getElementById('password').value = ""; // Vider le champ password
                document.getElementById('password').placeholder = "Laissez vide pour ne pas changer";

                userModal.classList.remove('hidden');
            }

            addUserBtn.addEventListener('click', setupCreateModal);

            tableBody.addEventListener('click', function(event) {
                const editBtn = event.target.closest('.edit-user-btn');
                if (editBtn) {
                    const userId = editBtn.dataset.id;
                    const updateUrl = editBtn.dataset.url;

                    fetch(`/users/${userId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            setupEditModal(data, updateUrl);
                        });
                }
            });

            closeModalBtn.addEventListener('click', () => userModal.classList.add('hidden'));
            cancelBtn.addEventListener('click', () => userModal.classList.add('hidden'));
        });
    </script>
@endpush

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ORMVAM Recouvrement')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar (Menu 3la lisser) -->
        <div class="hidden md:flex flex-col w-64 bg-gray-800 text-white">
            <div class="flex items-center justify-between h-16 px-4 bg-gray-900">
                <span class="font-bold uppercase">Recouvrement</span>
                <span class="text-sm">Irrigation</span>
            </div>
            <div class="flex flex-col flex-1 overflow-y-auto">
                {{-- Profile de l'utilisateur --}}
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold">
                            {{-- Premières lettres du nom --}}
                            {{ strtoupper(substr(Auth::user()->prenom, 0, 1) . substr(Auth::user()->nom, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                            <p class="text-sm text-gray-400">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                </div>

                {{-- Liens de navigation --}}
                <nav class="flex-1 px-2 py-4">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                        <i class="fas fa-tachometer-alt fa-fw mr-3"></i>Dashboard
                    </a>

                    {{-- MENU ADMINISTRATEUR --}}
                    @can('manage-users')
                        <p class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Administration</p>

                        <a href="{{ route('users.index') }}"
                            class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-users-cog fa-fw mr-3"></i>Gérer les Utilisateurs
                        </a>

                        <a href="{{ route('paiements.index') }}"
                            class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-edit fa-fw mr-3"></i>Modifier / Annuler RG8
                        </a>
                    @endcan



                    {{-- MENU RÉGISSEUR --}}
                    {{-- On vérifie si l'utilisateur est un Régisseur OU un Admin (l'admin peut tout voir) --}}
                    @if (Auth::user()->role === 'Régisseur')
                        <p class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">Opérations</p>

                        {{-- Enregistrer un Paiement (RG8) --}}
                        <a href="{{ route('rg8.create') }}"
                            class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-credit-card fa-fw mr-3"></i>Enregistrer un Paiement
                        </a>

                        {{-- Gérer les Factures --}}
                        <a href="{{ route('factures.index') }}"
                            class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-file-invoice fa-fw mr-3"></i>Gérer les Factures
                        </a>

                        {{-- Gérer les Clients --}}
                        <a href="{{ route('clients.index') }}"
                            class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-user-friends fa-fw mr-3"></i>Gérer les Clients
                        </a>

                        {{-- Générer RG12 --}}
                        <a href="{{ route('rg12.create') }}"
                            class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-file-alt fa-fw mr-3"></i>Générer RG12
                        </a>

                        {{-- Générer la Déclaration --}}
                        <a href="{{ route('declarations.create') }}"
                            class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-chart-bar fa-fw mr-3"></i>Générer la Déclaration
                        </a>
                    @endif
                </nav>

                {{-- Bouton de déconnexion --}}
                <div class="p-4 border-t border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <i class="fas fa-sign-out-alt fa-fw mr-3"></i>Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-y-auto">
            <main class="flex-1 p-4 sm:p-8">
                {{-- ########## HNA FIN GHADI YJI L'CONTENU DYAL KOL PAGE ########## --}}
                @yield('content')
                {{-- ############################################################### --}}
            </main>
        </div>
    </div>

    {{-- Pour le JavaScript spécifique à chaque page --}}
    @stack('scripts')
</body>

</html>

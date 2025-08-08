<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <div class="p-4 sm:p-8">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Détails de l'Utilisateur</h1>
                        <p class="text-gray-500">Informations complètes du compte</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                        &larr; Retour à la liste
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nom Complet</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->nom }} {{ $user->prenom }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nom d'utilisateur</p>
                        <p class="text-lg text-gray-800">{{ $user->identifiant_connexion }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="text-lg text-gray-800">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rôle</p>
                        <p class="text-lg font-semibold {{ $user->role === 'Admin' ? 'text-green-600' : 'text-orange-600' }}">{{ $user->role }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Compte créé le</p>
                        <p class="text-lg text-gray-800">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                     <div>
                        <p class="text-sm font-medium text-gray-500">Dernière modification</p>
                        <p class="text-lg text-gray-800">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
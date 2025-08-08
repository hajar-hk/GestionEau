<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // On récupère tous les utilisateurs
    $users = User::orderBy('role', 'asc')->orderBy('nom', 'asc')->get();
    // On calcule les statistiques directement depuis la base de données pour être précis
    $totalUsers = User::count();
    $totalRegisseurs = User::where('role', 'Régisseur')->count();
    $totalAdmins = User::where('role', 'Admin')->count();

    // On envoie toutes les données à la vue
    return view('users.index', [
        'users' => $users,
        'totalUsers' => $totalUsers,
        'totalComptesActifs' => $totalUsers, // On suppose qu'ils sont tous actifs pour le moment
        'totalRegisseurs' => $totalRegisseurs,
        'totalAdmins' => $totalAdmins,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // On ne fait rien ici car le formulaire est un modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string'],
             'identifiant_connexion' => ['required', 'string', 'unique:users,identifiant_connexion'], 
           ]);
           
         //dd($validatedData); pour verifier wsf 

        // On utilise une transaction pour la sécurité des données
        DB::beginTransaction(); // ==> 1. NBDAW L'TRANSACTION

        try {
            // 2. Création de l'utilisateur
            User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'identifiant_connexion' => $request->identifiant_connexion,
            ]);
        
            DB::commit(); // ==> 2. ILA DAKCHI DAZ MZYAN, N'SAUVEGARDIW

            // 3. Redirection avec un message de succès
            return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack(); // ==> 3. ILA WQE3 CHI MOCHKIL, N'ANNULIW KOLCHI
            
            // 4. Redirection avec un message d'erreur détaillé
            return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage())->withInput();    
        }
    }


      public function edit(User $user) // Laravel kayjib lina l'user automatiquement men l'ID
     {
    // Hna, bdl man'rje3o une vue, ghadi n'rje3o ghir les données f format JSON
    return response()->json($user);
     }



     public function update(Request $request, User $user)
  {
    // 1. Validation 
    $validatedData = $request->validate([
        'nom' => ['required', 'string', 'max:255'],
        'prenom' => ['required', 'string', 'max:255'],
        // L'règle 'unique' khassha t'tjahal l'user l'7ali, ila bdlna ghir smito
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'identifiant_connexion' => ['required', 'string', 'unique:users,identifiant_connexion,' . $user->id],
        'role' => ['required', 'string'],
        'password' => ['nullable', 'string', 'min:8'], // nullable = machi darori y'bdel l'password
    ]);

    // 2. Mise à jour des données
    $user->nom = $validatedData['nom'];
    $user->prenom = $validatedData['prenom'];
    $user->email = $validatedData['email'];
    $user->identifiant_connexion = $validatedData['identifiant_connexion'];
    $user->role = $validatedData['role'];

    // N'bdlo l'password ghir ila dkhel chi 7aja jdida
    if (!empty($validatedData['password'])) {
        $user->password = Hash::make($validatedData['password']);
    }

    $user->save(); // Enregistrer les changements

    // 3. Redirection avec un message de succès
    return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès!');
  }



  public function destroy(Request $request,User $user)
  {
      // On ajoute une petite sécurité pour empêcher un utilisateur de se supprimer lui-même
      if ($request->user()->id == $user->id) {      
            return redirect()->route('users.index')->with('error', 'Vous не pouvez pas supprimer votre propre compte.');
      }
  
      // On supprime l'utilisateur
      $user->delete();
  
      // On redirige avec un message de succès
      return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès!');
  }

  public function show(User $user)
{
    // On passe l'utilisateur trouvé à une nouvelle vue 'users.show'
    return view('users.show', ['user' => $user]);
}
}
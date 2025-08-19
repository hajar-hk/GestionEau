<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{


    public function index()
    {
        $users = User::orderBy('role', 'asc')->orderBy('nom', 'asc')->get();
        $totalUsers = $users->count();
        $totalRegisseurs = $users->where('role', 'Régisseur')->count();
        $totalAdmins = $users->where('role', 'Admin')->count();
        $totalComptesActifs = $users->count();


        return view('users.index', compact('users', 'totalUsers', 'totalRegisseurs', 'totalAdmins',  'totalComptesActifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string'],
            'identifiant_connexion' => ['required', 'string', 'unique:users,identifiant_connexion'],
        ]);

        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'identifiant_connexion' => $request->identifiant_connexion,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'identifiant_connexion' => ['required', 'string', 'unique:users,identifiant_connexion,' . $user->id],
            'role' => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $dataToUpdate = $request->except('password');
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }
        $user->update($dataToUpdate);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès!');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id == $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès!');
    }
}

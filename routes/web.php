<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rg8Controller;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Route dashboard protégée
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Groupe de routes pour les utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Routes du profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes RG8
    Route::get('/rg8/create', [Rg8Controller::class, 'create'])->name('rg8.create');
    Route::post('/rg8', [Rg8Controller::class, 'store'])->name('rg8.store');
    Route::get('/clients/search', [Rg8Controller::class, 'searchClient'])->name('clients.search');
    
    // On désactive la protection pour le moment
    Route::resource('users', UserController::class); // ->middleware('can:manage-users');

    // Gérer les Factures
    Route::get('/factures', function () {
        return "<h1>Page: Gérer les Factures (en construction)</h1>";
    })->name('factures.index');

    // Gérer les Clients
    Route::resource('clients', ClientController::class);
    
    // Générer RG12
    Route::get('/rg12/generer', function () {
        return "<h1>Page: Générer RG12 (en construction)</h1>";
    })->name('rg12.create');

    // Générer la Déclaration
    Route::get('/declarations/generer', function () {
        return "<h1>Page: Générer la Déclaration (en construction)</h1>";
    })->name('declarations.create');

    
});

// On inclut les routes d'authentification générées par Breeze
require __DIR__.'/auth.php';
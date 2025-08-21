<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rg8Controller;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\Rg12Controller;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\StatistiqueController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Route dashboard protégée
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

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

    // Gérer les Régisseur ( pour l'amdin) 
    Route::resource('users', UserController::class)->middleware('can:manage-users');

    // Route pour la gestion des paiements (Admin seulement)
    Route::resource('paiements', PaiementController::class)->middleware('can:manage-users');
    Route::get('/paiements/{paiement}/print', [App\Http\Controllers\PaiementController::class, 'print'])->name('paiements.print');

    // Route pour les statistiques (Admin seulement)
    Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index')->middleware('can:manage-users');

    // Gérer les Factures
    Route::get('/factures/export', [FactureController::class, 'export'])->name('factures.export');
    Route::resource('factures', FactureController::class);

    // Gérer les Clients
    Route::resource('clients', ClientController::class);

    // Générer RG12
    Route::get('/rg12/generer', [Rg12Controller::class, 'create'])->name('rg12.create'); // get bech afficher lina la page
    Route::post('/rg12', [Rg12Controller::class, 'store'])->name('rg12.store'); //Ghadi t'steqbel les paiements l'm'selectiyin o t'créé l'RG12.
    Route::get('/rg12/{bordereau}/print', [RG12Controller::class, 'print'])->name('rg12.print');

    // Générer la Déclaration
    Route::resource('declarations', DeclarationController::class);
});

// On inclut les routes d'authentification générées par Breeze
require __DIR__ . '/auth.php';

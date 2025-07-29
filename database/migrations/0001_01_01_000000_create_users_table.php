<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id_utilisateur (PK)
            $table->string('nom');
            $table->string('prenom'); 
            $table->string('identifiant_connexion')->unique(); 
            $table->timestamp('email_verified_at')->nullable(); // On peut le laisser ou le supprimer
            $table->string('password'); // mot_de_passe_hache
            $table->string('role'); // AJOUT (Admin, Régisseur...)
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
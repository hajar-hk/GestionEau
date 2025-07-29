<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id(); // id_facture (PK)
            $table->string('numero_facture')->unique();
            $table->decimal('montants');
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->string('statut'); // Ex: 'Payée', 'Non Payée'

            // La clé étrangère (FK) vers la table clients
            $table->foreignId('client_id')->constrained('clients');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};

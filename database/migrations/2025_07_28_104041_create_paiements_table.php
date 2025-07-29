<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id(); // id_paiement_rg8 (PK)
            $table->string('numero_rg8')->unique();
            $table->decimal('montant_paye', 10, 2);
            $table->decimal('penalite_retard', 10, 2)->default(0);
            $table->dateTime('date_paiement');
            $table->string('motif_annulation')->nullable(); // nullable() = tqder tkon khawya

            // Les clés étrangères (FKs)
            $table->foreignId('bordereau_rg12_id')->constrained('bordereaux_rg12');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('facture_id')->constrained('factures');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements_rg8');
    }
};

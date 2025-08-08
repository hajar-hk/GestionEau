<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements_rg8', function (Blueprint $table) {
            $table->id();
            $table->string('numero_rg8')->unique();
            $table->string('numero_recu')->nullable();
            $table->string('methode_reglement')->nullable();
            $table->decimal('montant_paye');
            $table->decimal('penalite_retard')->default(0);
            $table->dateTime('date_paiement');
            $table->string('motif_annulation')->nullable();
            $table->foreignId('bordereau_rg12_id')->nullable()->constrained('bordereaux_rg12');
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
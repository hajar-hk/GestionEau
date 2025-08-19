<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bordereaux_rg12', function (Blueprint $table) {
            $table->id(); // id_bordereau_rg12 (PK)
            $table->string('numero_rg12')->unique();
            $table->dateTime('date_creation');
            $table->decimal('montant_total', 10, 2);

            // La clé étrangère (FK) vers la table declarations
            $table->foreignId('declaration_id')->nullable()->constrained('declarations');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bordereaux_rg12');
    }
};

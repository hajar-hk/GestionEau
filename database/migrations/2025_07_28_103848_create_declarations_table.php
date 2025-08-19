<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->id();
            $table->string('numero_declaration')->unique();
            $table->string('periode');
            $table->decimal('montant_global', 15, 2);
            $table->date('date_declaration');
            $table->enum('statut', ['Soumise', 'Validée'])->default('Soumise');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('declarations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->id(); // id_declaration (PK)
            $table->string('numero_declaration')->unique();
            $table->date('date_declaration');
            $table->decimal('montant_global'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('declarations');
    }
};

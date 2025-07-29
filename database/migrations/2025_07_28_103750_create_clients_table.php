<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // id_client (PK)
            $table->string('code_client')->unique();
            $table->string('nom_client');
            $table->string('prenom_client');
            $table->string('telephone')->nullable(); 
            $table->timestamps();  // ( pour dek : created_at et updated_at )
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
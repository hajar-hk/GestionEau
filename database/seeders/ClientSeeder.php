<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 10 clients aléatoires
        \App\Models\Client::factory(10)->create();
    }
};

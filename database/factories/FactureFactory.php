<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facture>
 */
class FactureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ht = $this->faker->numberBetween(500, 5000);

        return [
            'numero_facture' => 'F-' . date('Y') . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'client_id' => Client::inRandomOrder()->first()->id,
            'montants' => $ht,
            'statut' => $this->faker->randomElement(['Payée', 'En attente', 'En retard']),
            'semestre' => 'S' . $this->faker->numberBetween(1, 2) . ' ' . date('Y'),
            'date_emission' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'date_echeance' => $this->faker->dateTimeBetween('now', '+2 months'),
        ];
    }
}

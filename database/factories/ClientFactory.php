<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code_client' => 'C' . $this->faker->unique()->numberBetween(1000, 9999),
            'nom_client' => $this->faker->lastName,
            'prenom_client' => $this->faker->firstName,
            'telephone' => $this->faker->phoneNumber,
            'secteur' => $this->faker->randomElement(['Nord', 'Sud', 'Est', 'Ouest', 'Centre']),
            'email' => $this->faker->unique()->safeEmail(),
            'statut' => 'Actif',
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'nama_menu' => fake()->words(3, true),
            'harga' => fake()->numberBetween(5000, 50000),
            'stok' => fake()->numberBetween(1, 100),
            'area_kampus' => fake()->randomElement(['Kampus A', 'Kampus B', 'Kampus C']),
            'nama_warung' => fake()->company() . ' Food',
            'gambar' => null, // Default no image for testing
        ];
    }
}

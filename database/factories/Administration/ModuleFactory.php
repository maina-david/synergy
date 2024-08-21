<?php

namespace Database\Factories\Administration;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Administration\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['business', 'technology', 'finance'];

        return [
            'icon' => fake()->imageUrl(50, 50, fake()->randomElement($categories), true, 'icon', true, 'png'),
            'banner' => fake()->imageUrl(800, 600, fake()->randomElement($categories), true, 'banner', true, 'jpg'),
        ];
    }
}
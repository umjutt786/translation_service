<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale' => fake()->randomElement(['en', 'fr', 'es']),
            'tags'   => [fake()->randomElement(['web', 'mobile', 'desktop'])],
            'key'    => fake()->word() . '_' . fake()->unique()->numberBetween(1, 1000000),
            'value'  => fake()->sentence(),
        ];
    }
}

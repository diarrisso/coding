<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teaser>
 */
class TeaserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'slug' => $this->faker->slug(),
            'image_name' => 'default-factory-image.jpg', // Default image name to satisfy NOT NULL constraint
            'user_id' => \App\Models\User::factory(),
        ];
    }
}

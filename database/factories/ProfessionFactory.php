<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'meta_description' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'avatar' => $this->faker->imageUrl(),
        ];
    }
}

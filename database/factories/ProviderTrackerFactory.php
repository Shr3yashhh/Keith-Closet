<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderTrackerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'provider_id' => $this->faker->numberBetween(1, 10),
            'current_latitude' => $this->faker->latitude,
            'current_longitude' => $this->faker->longitude,
            'last_latitude' => $this->faker->latitude,
            'last_longitude' => $this->faker->longitude,

        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserTrackerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'current_latitude' => $this->faker->latitude,
            'current_longitude' => $this->faker->longitude,
            'last_latitude' => $this->faker->latitude,
            'last_longitude' => $this->faker->longitude,

        ];
    }
}

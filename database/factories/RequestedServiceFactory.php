<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RequestedServiceFactory extends Factory
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
            'provider_id' => $this->faker->numberBetween(1, 10),
            'user_latitude' => $this->faker->latitude,
            'user_longitude' => $this->faker->longitude,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'rejected','cancelled']),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}

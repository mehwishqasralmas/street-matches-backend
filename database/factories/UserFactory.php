<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          "first_name" => $this->faker->firstName(),
          "last_name" => $this->faker->lastName(),
          "email" => $this->faker->email(),
          "password" => password_hash("12345", null),
          "phone_number" => $this->faker->phoneNumber(),
          "birthdate" => $this->faker->date('Y-m-d', now()->subYears(15)),
          "type" => 'PLAYER',
          "location_long" => $this->faker->numberBetween(-180, 180),
          "location_lat" => $this->faker->numberBetween(-90, 90),
          "address" => $this->faker->address(),
          "email_verified_at" => null,
          "remember_token" => null
        ];
    }
}

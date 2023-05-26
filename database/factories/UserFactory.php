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
          "first_name" => "",
          "last_name" => "",
          "email" => "",
          "password" => "",
          "phone_number" => "",
          "birthdate" => "",
          "type" => "",
          "first_name" => "",
          "first_name" => "",
        ];
    }
}

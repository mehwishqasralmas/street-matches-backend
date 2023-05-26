<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User as UserModel;
use App\Models\player as PlayerModel;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PlayerFactory extends Factory
{

    public function configure()
    {
      return $this->sequence(function (Sequence $seq) {
        return [
          "position" => PlayerModel::$POSTIONS[$seq->index % count(PlayerModel::$POSTIONS)]["code"]
        ];
      });
    }

  public function definition()
    {
        return [
          "dominate_foot" => $this->faker->randomElement(["RIGHT", "LEFT"]),
          "weight" => $this->faker->numberBetween(60, 80),
          "height" => $this->faker->numberBetween(130, 200),
          "year_active" => $this->faker->year(),
          "first_name" => function ($attributes) { return UserModel::find($attributes['user_id'])->first_name;},
          "last_name" => function ($attributes) { return UserModel::find($attributes['user_id'])->last_name; },
          "birthdate" => function ($attributes) { return UserModel::find($attributes['user_id'])->birthdate;},
          "location_long" => function ($attributes) { return UserModel::find($attributes['user_id'])->location_long;},
          "location_lat" => function ($attributes) { return UserModel::find($attributes['user_id'])->location_lat;},
          "creator_user_id" => function ($attributes) { return $attributes['user_id'];},
          "description" => $this->faker->text(150),
          "address" => function ($attributes) { return UserModel::find($attributes['user_id'])->address; },
          "description_cn" => $this->faker->text(50),
        ];
    }

}

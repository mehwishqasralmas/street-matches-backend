<?php

namespace Database\Seeders;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()
           ->count(10)
           ->has(PlayerFactory::new()->count(1), "player")
           ->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            CountriesSeeder::class,
            StatesSeeder::class,
            CitiesSeeder::class,
            CitiesTwoSeeder::class,
            CitiesThreeSeeder::class,
            CitiesFourSeeder::class,
            CitiesFiveSeeder::class,
            AnimalTypesSeeder::class,
            UsersSeeder::class,
            SubscriptionsSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}

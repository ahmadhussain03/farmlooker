<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::query()->truncate();
        Schema::enableForeignKeyConstraints();

        $faker = Factory::create();

        User::create([
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => 'admin@farmlooker.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'), // password
            'remember_token' => Str::random(10),
            'phone_no' => $faker->phoneNumber(),
            'experience' => '3 years'
        ]);
    }
}

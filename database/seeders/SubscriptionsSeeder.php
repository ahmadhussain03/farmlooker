<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SubscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Subscription::query()->truncate();
        Schema::enableForeignKeyConstraints();

        Subscription::create([
            'title' => 'Subscription 1',
            'description' => 'Description 1',
            'amount' => 100
        ]);

        Subscription::create([
            'title' => 'Subscription 2',
            'description' => 'Description 2',
            'amount' => 200
        ]);

        Subscription::create([
            'title' => 'Subscription 3',
            'description' => 'Description 3',
            'amount' => 300
        ]);
    }
}

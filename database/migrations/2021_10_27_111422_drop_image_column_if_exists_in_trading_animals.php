<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropImageColumnIfExistsInTradingAnimals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('trading_animals', 'image')) {
            Schema::table('trading_animals', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('trading_animals', function (Blueprint $table) {
        //     //
        // });
    }
}

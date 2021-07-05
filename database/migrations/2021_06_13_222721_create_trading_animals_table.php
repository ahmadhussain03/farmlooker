<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradingAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trading_animals', function (Blueprint $table) {
            $table->id();

            $table->string('image');
            $table->string('type');
            $table->float('price');
            $table->date('dob');
            $table->text('location');
            $table->date('dated');

            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trading_animals');
    }
}

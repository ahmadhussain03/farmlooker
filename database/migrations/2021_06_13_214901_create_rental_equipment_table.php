<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_equipment', function (Blueprint $table) {
            $table->id();

            $table->string('image');
            $table->string('name');
            $table->string('model');
            $table->float('rent');
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
        Schema::dropIfExists('rental_equipment');
    }
}

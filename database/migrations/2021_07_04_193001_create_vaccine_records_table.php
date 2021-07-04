<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccineRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('animal_id')->constrained()->onDelete('CASCADE');

            $table->string('name');
            $table->text('reason');
            $table->date('date');

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
        Schema::dropIfExists('vaccine_records');
    }
}

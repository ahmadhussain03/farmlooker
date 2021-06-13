<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_alerts', function (Blueprint $table) {
            $table->id();

            $table->text('description');
            $table->json('symptoms');

            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('animal_id')->constrained()->onDelete('CASCADE');

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
        Schema::dropIfExists('disease_alerts');
    }
}

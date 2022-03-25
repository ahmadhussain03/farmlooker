<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_infos', function (Blueprint $table) {
            $table->id();

            $table->string('t_min');
            $table->string('t_max');
            $table->string('wind_speed');
            $table->string('cloud_cover');
            $table->string('humidity');
            $table->string('rainfall');
            $table->text('msavi');
            $table->text('ndre');
            $table->text('recl');
            $table->text('ndvi');

            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
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
        Schema::table('farm_infos', function (Blueprint $table){
            $table->dropForeign(['farm_id']);
        });
        Schema::dropIfExists('farm_infos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHerdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('herds', function (Blueprint $table) {
            $table->id();

            $table->string('name');

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
        Schema::table('herds', function(Blueprint $table){
            $table->dropForeign(['farm_id']);
        });
        Schema::dropIfExists('herds');
    }
}

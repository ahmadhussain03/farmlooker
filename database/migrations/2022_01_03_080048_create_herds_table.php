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
        Schema::disableForeignKeyConstraints();
        // Schema::table('herds', function($table){
        //     $table->dropForeign(['farm_id']);
        // });
        DB::raw('DROP TABLE if exists herds cascade');
        Schema::dropIfExists('herds');
        Schema::enableForeignKeyConstraints();
    }
}

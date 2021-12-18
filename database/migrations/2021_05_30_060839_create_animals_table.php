<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();

            $table->string('animal_id');
            $table->enum('add_as', ['purchased', 'calved']);
            $table->string('sex');
            $table->date('dob');
            $table->date('purchase_date')->nullable();
            $table->enum('disease', ['healthy', 'sick']);
            $table->double('price')->nullable();
            $table->string('previous_owner')->nullable();

            $table->foreignId('type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('breed_id')->constrained()->cascadeOnDelete();
            $table->foreignId('male_breeder_id')->nullable()->constrained('animals')->onDelete('CASCADE');
            $table->foreignId('female_breeder_id')->nullable()->constrained('animals')->onDelete('CASCADE');
            $table->foreignId('farm_id')->constrained()->onDelete('CASCADE');

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
        Schema::dropIfExists('animals');
    }
}

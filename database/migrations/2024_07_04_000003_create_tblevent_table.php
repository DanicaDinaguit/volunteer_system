<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbleventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblevent', function (Blueprint $table) {
            $table->id('eventID');
            $table->string('title');
            $table->time('event_start'); 
            $table->time('event_end');
            $table->date('event_date');
            $table->text('description')->nullable();
            $table->integer('number_of_volunteers')->nullable();
            $table->string('partnership')->nullable();
            $table->string('event_location')->nullable();
            $table->string('category');
            $table->string('event_status');
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
        Schema::dropIfExists('tblevent');
    }
}

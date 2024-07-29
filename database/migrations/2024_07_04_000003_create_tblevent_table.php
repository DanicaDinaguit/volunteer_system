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
            $table->string('event_name');
            $table->time('event_time');
            $table->date('event_date');
            $table->text('description');
            $table->integer('number_of_volunteers');
            $table->string('event_location');
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

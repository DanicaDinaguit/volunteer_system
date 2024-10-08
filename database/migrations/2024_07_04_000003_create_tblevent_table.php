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
            $table->id('id');
            $table->string('title');
            $table->time('start'); 
            $table->time('end');
            $table->date('event_date');
            $table->text('description');
            $table->integer('number_of_volunteers');
            $table->integer('volunteers_joined')->default(0);
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblpositionTable extends Migration
{
    public function up()
    {
        Schema::create('tblposition', function (Blueprint $table) {
            $table->id('positionID');
            $table->string('position_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tblposition');
    }
}

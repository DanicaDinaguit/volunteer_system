<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblparticipantsTable extends Migration
{
    public function up()
    {
        Schema::create('tblparticipants', function (Blueprint $table) {
            $table->id('participantsID');
            $table->unsignedBigInteger('memberCredentialsID');
            $table->unsignedBigInteger('eventID');
            $table->timestamps();

            $table->foreign('memberCredentialsID')->references('memberCredentialsID')->on('tblmembercredentials')->onDelete('cascade');
            $table->foreign('eventID')->references('id')->on('tblevent')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tblparticipants');
    }
}


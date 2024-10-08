<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmembercredentialsTable extends Migration
{
    public function up()
    {
        Schema::create('tblmembercredentials', function (Blueprint $table) {
            $table->id('memberCredentialsID');
            $table->unsignedBigInteger('positionID');
            $table->unsignedBigInteger('memberApplicationID');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('studentID')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->text('aboutMe')->nullable();
            $table->timestamps();

            $table->foreign('positionID')->references('positionID')->on('tblposition')->onDelete('cascade');
            $table->foreign('memberApplicationID')->references('memberApplicationID')->on('tblmemberapplication')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tblmembercredentials');
    }
}


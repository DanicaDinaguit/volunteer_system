<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmemberapplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblmemberapplication', function (Blueprint $table) {
            $table->id('memberApplicationID');
            $table->string('name');
            $table->string('phone_number', 15);
            $table->string('email_address');
            $table->integer('age');
            $table->string('address');
            $table->string('religion', 50);
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('citizenship', 50);
            $table->enum('civil_status', ['Single', 'Married', 'Divorced', 'Widowed']);
            $table->string('college');
            $table->string('course');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year']);
            $table->string('schoolID', 50);
            $table->string('high_school');
            $table->string('elementary');
            $table->text('reasons_for_joining');
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
        Schema::dropIfExists('tblmemberapplication');
    }
}

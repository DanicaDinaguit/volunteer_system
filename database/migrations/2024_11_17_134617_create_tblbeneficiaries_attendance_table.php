<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBeneficiariesAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblbeneficiaries_attendance', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('eventID'); // Foreign key for event
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('purok')->nullable(); // New purok field
            $table->date('date_attended'); // To match the event's date
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('eventID')->references('id')->on('tblevent')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblbeneficiaries_attendance');
    }
}

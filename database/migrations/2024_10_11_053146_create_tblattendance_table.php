<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblattendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblattendance', function (Blueprint $table) {
            $table->id('id');
            
            // Foreign key reference to tblparticipants
            $table->unsignedBigInteger('participantsID');
            $table->foreign('participantsID')
                  ->references('participantsID')
                  ->on('tblparticipants')
                  ->onDelete('cascade');
            
            // Additional fields from QR data
            $table->string('studentID');  // Unique identifier for students
            $table->string('full_name');   // Full name of the participant
            $table->string('course');       // Course of the participant
            $table->enum('status', ['Present', 'Late', 'Absent'])->nullable();
            // Attendance timestamps
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            
            // Timestamps for created_at and updated_at
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
        Schema::dropIfExists('tblattendance');
    }
}

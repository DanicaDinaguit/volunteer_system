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
            $table->string('first_name')->after('memberApplicationID');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');
            $table->string('phone_number', 15);
            $table->string('email_address');
            $table->date('birthdate')->default('2000-01-01')->after('email_address'); // Example default date
            
            // Add detailed address columns
            $table->string('street_address')->nullable()->after('birthdate');
            $table->string('city')->nullable()->after('street_address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->nullable()->default('Philippines')->after('postal_code');

            $table->string('religion', 50);
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('citizenship', 50);
            $table->enum('civil_status', ['Single', 'Married', 'Divorced', 'Widowed']);
            $table->string('college');
            $table->string('course');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year']);
            $table->string('schoolID', 50);
            $table->string('high_school')->nullable();
            $table->string('elementary')->nullable();
            $table->text('reasons_for_joining');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
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

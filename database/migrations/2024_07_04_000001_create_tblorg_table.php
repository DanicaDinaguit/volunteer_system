<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblorgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblorg', function (Blueprint $table) {
            $table->id('orgID');
            $table->string('org_name');
            $table->text('org_details')->nullable();
            $table->date('established_date');
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->string('type');
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
        Schema::dropIfExists('tblorg');
    }
}


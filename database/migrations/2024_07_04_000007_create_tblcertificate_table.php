<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTBLCERTIFICATETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBLCERTIFICATE', function (Blueprint $table) {
            $table->id('certID');
            $table->unsignedBigInteger('memberCredentialsID');
            $table->unsignedBigInteger('eventID');
            $table->string('certificate_name');
            $table->date('dateIssued')->nullable();
            $table->string('status')->nullable();
            $table->string('issued_by')->nullable();
            $table->text('certificate_description')->nullable();
            $table->timestamps();

            $table->foreign('memberCredentialsID')->references('memberCredentialsID')->on('TBLMEMBERCREDENTIALS')->onDelete('cascade');
            $table->foreign('eventID')->references('eventID')->on('TBLEVENT')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TBLCERTIFICATE');
    }
}

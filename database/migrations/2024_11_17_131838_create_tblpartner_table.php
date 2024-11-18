<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tblpartner', function (Blueprint $table) {
            $table->id('id'); // Primary Key
            $table->string('partner_name'); // Partner name
            $table->string('contact_person')->nullable(); // Contact person for the partner
            $table->string('email')->nullable(); // Partner email
            $table->string('phone')->nullable(); // Partner phone
            $table->string('address')->nullable(); // Partner address
            $table->text('description')->nullable(); // Description or additional info
            $table->date('date_partnered')->nullable(); 
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tblpartner');
    }
};

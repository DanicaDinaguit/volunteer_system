<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tblmembercredentials', function (Blueprint $table) {
            $table->unsignedBigInteger('memberApplicationID')->after('positionID');
            $table->foreign('memberApplicationID')->references('memberApplicationID')->on('tblmemberapplication')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tblmembercredentials', function (Blueprint $table) {
            $table->dropForeign(['memberApplicationID']);
            $table->dropColumn('memberApplicationID');
        });
    }
};

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
        Schema::table('tblevent', function (Blueprint $table) {
            $table->integer('volunteers_joined')->default(0)->after('number_of_volunteers');
        });
    }

    public function down()
    {
        Schema::table('tblevent', function (Blueprint $table) {
            $table->dropColumn('volunteers_joined');
        });
    }
};

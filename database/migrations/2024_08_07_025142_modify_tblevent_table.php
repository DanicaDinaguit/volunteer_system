<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTbleventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblevent', function (Blueprint $table) {
            // Add new columns
            $table->time('event_start')->after('event_date'); // Position as needed
            $table->time('event_end')->after('event_start'); // Position as needed
            
            // Remove old column
            $table->dropColumn('event_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblevent', function (Blueprint $table) {
            // Re-add the old column
            $table->time('event_time')->after('event_date'); // Position as needed

            // Remove new columns
            $table->dropColumn('event_start');
            $table->dropColumn('event_end');
        });
    }
}

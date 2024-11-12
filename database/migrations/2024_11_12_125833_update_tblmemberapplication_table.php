<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTblmemberapplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblmemberapplication', function (Blueprint $table) {
            // Add new columns for name, birthdate, and detailed address
            $table->string('first_name')->after('memberApplicationID');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');
            $table->date('birthdate')->default('2000-01-01')->after('email_address'); // Example default date

            
            // Add detailed address columns
            $table->string('street_address')->nullable()->after('birthdate');
            $table->string('city')->after('street_address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->default('Philippines')->after('postal_code');
            
            // Drop old columns
            $table->dropColumn('name');
            $table->dropColumn('age');
            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblmemberapplication', function (Blueprint $table) {
            // Revert by adding back the original columns
            $table->string('name')->after('memberApplicationID');
            $table->integer('age')->after('email_address');
            $table->string('address')->after('age');
            
            // Drop the new columns
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'birthdate']);
            $table->dropColumn(['street_address', 'city', 'state', 'postal_code', 'country']);
        });
    }
}

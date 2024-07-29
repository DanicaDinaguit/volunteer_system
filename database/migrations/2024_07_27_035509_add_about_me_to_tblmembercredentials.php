<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAboutMeToTblmembercredentials extends Migration
{
    public function up()
    {
        Schema::table('tblmembercredentials', function (Blueprint $table) {
            $table->text('aboutMe')->nullable()->after('password');
        });
    }

    public function down()
    {
        Schema::table('tblmembercredentials', function (Blueprint $table) {
            $table->dropColumn('aboutMe');
        });
    }
}

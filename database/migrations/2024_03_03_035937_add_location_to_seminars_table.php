<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seminars', function (Blueprint $table) {
            $table->string('location')->after('date'); // Adds the 'location' column after the 'date' column
        });
    }

    public function down()
    {
        Schema::table('seminars', function (Blueprint $table) {
            $table->dropColumn('location'); // Removes the 'location' column
        });
    }
};

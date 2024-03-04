<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seminars', function (Blueprint $table) {
            // Add a 'time' column
            $table->time('time')->nullable()->after('date');
        });
    }

    public function down()
    {
        Schema::table('seminars', function (Blueprint $table) {
            // Remove the 'time' column if the migration is rolled back
            $table->dropColumn('time');
        });
    }
};

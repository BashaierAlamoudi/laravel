<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->timestamps();
            $table->string('supervisor')->nullable();
            $table->string('coSupervisor')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('section')->nullable();
            $table->string('department')->nullable();
            $table->date('thesisStartDate')->nullable();
            $table->string('field')->nullable();
            $table->integer('semesterNumber')->nullable();
            $table->integer('remainingSemesters')->nullable();
            $table->integer('remainingCourses')->nullable();
            $table->integer('dissertationStartYear')->nullable();
            $table->integer('numberOfSemesters')->nullable();
            $table->integer('numberOfSeminars')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropColumn('supervisor');
            $table->dropColumn('coSupervisor');
            $table->dropColumn('phoneNumber');
            $table->dropColumn('section');
            $table->dropColumn('department');
            $table->dropColumn('thesisStartDate');
            $table->dropColumn('field');
            $table->dropColumn('enrollYear');
            $table->dropColumn('semesterNumber');
            $table->dropColumn('remainingSemesters');
            $table->dropColumn('remainingCourses');
            $table->dropColumn('dissertationStartYear');
            $table->dropColumn('numberOfSemesters');
            $table->dropColumn('numberOfSeminars');
        });
    }
};

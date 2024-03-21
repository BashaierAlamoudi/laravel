<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loginId')->UNIQUE();
            $table->date('dateOfBirth'); 
            $table->date('graduationDate'); 
            $table->integer('withdrawSemester'); 
            $table->integer('postponedSemester'); 
            $table->string('status');
            $table->date('enrollYear');
            $table->float('gpa', 4, 2);

        });
        Schema::table('student', function (Blueprint $table) {
        $table->foreign('loginId')
                  ->references('id') // Correct the column name to match the student table
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');

    }
};

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
            $table->unsignedBigInteger('studentId')->UNIQUE();
            $table->string('password');
            $table->string('firstName'); 
            $table->string('middleName'); 
            $table->string('lastName'); 
            $table->string('phone_number', 10); 
            $table->string('email')->unique();
            $table->date('dateOfBirth'); 
            $table->date('graduationDate'); 
            $table->integer('withdrawSemester'); 
            $table->integer('PostponedSemester'); 
            $table->string('status');
            $table->date('enrollYear');
            $table->string('department');
            $table->float('gpa', 4, 2);

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

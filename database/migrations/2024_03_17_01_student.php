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
            $table->timestamps();
            $table->unsignedBigInteger('userId')->unique();
            //$table->string('graduationDate'); 
           //$table->integer('withdrawSemester'); 
           //$table->integer('postponedSemester'); 
            $table->string('status');
            $table->string('field');
            $table->string('enrollYear');
            $table->float('gpa', 4, 2);
            $table->date('thesisStartDate')->nullable();
            
        });
        Schema::table('student', function (Blueprint $table) {
        $table->foreign('userId')
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

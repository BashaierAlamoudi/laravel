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
        Schema::create('supervise', function (Blueprint $table) {
            $table->id('superviseId');
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('instructorId');

            
        });
        Schema::table('supervise',function($table){ 
            $table->foreign('studentId')
                  ->references('id')
                  ->on('student')
                  ->onDelete('cascade');
            $table->foreign('instructorId')
                        ->references('id')
                        ->on('instructor')
                        ->onDelete('cascade'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervice');

    }
};

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
        Schema::create('comp', function (Blueprint $table) {
            $table->id();
            $table->string('studentName');
            $table->integer('numAttempts');
            $table->string('examName');
            $table->integer('score');
            $table->date('date');
            $table->timestamps();
            // $table->foreign('studentId')->references('id')->on('students')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comp');
    }
};

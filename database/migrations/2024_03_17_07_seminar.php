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
    Schema::create('seminar', function (Blueprint $table) {
        $table->id('seminarId'); 
        $table->unsignedBigInteger('userId'); // Corrected data type
        $table->string('title');
        $table->string('field');
        $table->date('date');
        $table->time('time');
        $table->string('location');
    });

    Schema::table('seminar', function ($table) {
        $table->foreign('userId')
            ->references('id')
            ->on('user')
            ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar');

    }
};

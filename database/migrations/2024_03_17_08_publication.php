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
        Schema::create('publication', function (Blueprint $table) {
            $table->id('publicationId');   
            $table->unsignedBigInteger('userId'); // Define the studentId column

            $table->string('title');
            $table->string('field');
            $table->date('date');
            $table->string('pdfPath');

        });  
        Schema::table('publication', function (Blueprint $table) {
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
        Schema::dropIfExists('publication');

    }
};

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
        Schema::create('comprehensive_exam', function (Blueprint $table) {
            
            $table->id('examId');
            $table->string('examName');
            $table->string('year');
            $table->string('season');
            $table->string('written_description')->nullable();
            $table->string('oral_description')->nullable();
            $table->string('written_pdfPath')->nullable();
            $table->string('oral_pdfPath')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprehensive_exam');

    }
};

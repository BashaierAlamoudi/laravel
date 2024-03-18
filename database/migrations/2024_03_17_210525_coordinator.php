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
        Schema::create('coordinator', function (Blueprint $table) {
            
            $table->id('coordinatorId');
            $table->unsignedBigInteger('instructorId');

            $table->string('password');
        });
        Schema::table('coordinator',function($table){
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
        Schema::dropIfExists('coorinator');

    }
};

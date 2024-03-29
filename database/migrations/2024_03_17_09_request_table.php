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
        Schema::create('request_table', function (Blueprint $table) {
            $table->id('requestId'); 
            $table->unsignedBigInteger('loginId'); // Corrected data type

            $table->string('approvalStatus');

            $table->string('requsetName');
            $table->date('date'); });

            Schema::table('request_table',function($table){
            $table->foreign('loginId')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');
            });

    }

   
    public function down(): void
    {
        Schema::dropIfExists('request');

    }
};

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
            $table->unsignedBigInteger('loginId');
            $table->unsignedBigInteger('supervisiorId');
            $table->string('type');

            
        });
        Schema::table('supervise',function($table){ 
            $table->foreign('loginId')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');
            $table->foreign('supervisiorId')
                        ->references('id')
                        ->on('supervisior')
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

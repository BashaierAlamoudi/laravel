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
        Schema::create('new_students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('userId')->unique();
            $table->unsignedBigInteger('nationalId')->unique();
            $table->string('password');
            $table->string('firstName');
            $table->string('middletName');
            $table->string('lastName');
            $table->string('phone_number', 10);
            $table->string('email',191)->unique();
            $table->string('department');
            $table->string('section');
            $table->date('enrollYear');
            $table->float('gpa', 4, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_students');
    }
};

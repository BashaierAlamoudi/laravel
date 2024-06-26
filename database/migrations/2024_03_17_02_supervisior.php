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
        Schema::create('supervisior', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supervisiorId');
            $table->string('firstName');
            $table->string('middleName');
            $table->string('lastName');
            $table->string('phone_number', 10);
            $table->string('email',191)->unique();
            $table->string('department');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisior');

    }
};

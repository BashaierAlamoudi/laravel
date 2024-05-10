<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AddStudents', function (Blueprint $table) {
            $table->id();
            $table->string('studentId')->unique();
            $table->string('password');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('lastName');
            $table->integer('enrollYear');
            $table->decimal('gpa', 3, 2);
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('department');
            $table->string('section');
            $table->string('nationalId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
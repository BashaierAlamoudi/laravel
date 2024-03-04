<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing `id` column (unsigned big integer) and makes it the primary key
            $table->string('name'); // Example: student name
            $table->string('email')->unique(); // Example: student email
            $table->date('dob'); // Example: date of birth
            $table->timestamps(); // Creates `created_at` and `updated_at` columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
    
}

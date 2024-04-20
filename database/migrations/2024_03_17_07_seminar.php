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
            $table->unsignedBigInteger('userId')->nullable(); // Nullable 
            $table->string('Name');
            $table->string('type'); 
            $table->string('title')->nullable();
            $table->string('field')->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('location');
    
            // Define foreign key constraint for userId, conditional based on type being 'student'
            $table->foreign('userId')
                ->references('id')
                ->on('user')
                ->onDelete('cascade')
                ->when('type', 'student'); // Conditional foreign key constraint
        });
        DB::statement('ALTER TABLE seminar ADD CONSTRAINT check_user_type CHECK ((type = \'student\' AND userId IS NOT NULL) OR (type != \'student\'))');

    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar');
    }
    
};

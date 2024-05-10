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
        Schema::create('progress_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });


        // Insert default data
        DB::table('progress_descriptions')->insert([
            ['title' => 'Comprehencive Exam', 'description' => ' After completing all the courses you can request the exam
            The exam two part written exam  and Oral Exam 
            The Written exam  is  ( Core courses & Elective Exam  )
            The Oral Exam (Technical report & Oral presentation) '],
            ['title' => 'Dissertation', 'description' => 'After passing the Comprehencive exam CPIT 799 is registered with your supervisor'],
            ['title' => 'Seminars Presented', 'description' => 'Annual Seminars is pressent the progress of your Dissertation every years must pressent'],
            ['title' => 'Seminars Attended', 'description' => 'you  are requried to attend at least 3 seminars per semester'],
            ['title' => 'Publications', 'description' => ' you are requried to publish at least two research papers  
            The paper must be from the thesis 
            One of this paper ISI with impact factor and other can be any journal or international conference'],
        ]);
    }  
     
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_descriptions');
    }
};

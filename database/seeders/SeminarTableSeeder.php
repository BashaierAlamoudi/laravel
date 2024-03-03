<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeminarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          
        DB::table('seminars')->insert([
            'studentId' => 2005069, // Use the first student's ID
            'studentName' =>'ghadah', // Adjust according to your Student model
            'title' => 'Effective Study Techniques',
            'field' => 'Education',
            'date' => now()->toDateString(),
            'location' => 'Link',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

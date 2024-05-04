<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('student')->insert([
            [

                'userId' => 1,
                'graduationDate' => '2026', 
                'withdrawSemester' => 0,
                'postponedSemester' => 0,
                'status' => 'active',
                'enrollYear' => '2024',
                'gpa' => 4.9,
            ],
            [
                'userId' => 2,
                'graduationDate' => '2024', 
                'withdrawSemester' => 0,
                'postponedSemester' => 0,
                'status' => 'active',
                'enrollYear' => '2022',
                'gpa' => 4.9,
            ],
            [
                'userId' => 4,
                'graduationDate' => '2024', 
                'withdrawSemester' => 0,
                'postponedSemester' => 0,
                'status' => 'active',
                'enrollYear' => '2022',
                'gpa' => 4.9,
            ],
 
            
        ]);
    
    }
}

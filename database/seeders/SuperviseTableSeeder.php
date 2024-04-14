<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperviseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('supervise')->insert([
            [
                'userId' => 1,
                'supervisiorId' => 1,
                'type' => 'main',
 
            ],
            [
                'userId' => 2,
                'supervisiorId' => 1,
                'type' => 'main',
 
            ],
            [
                'userId' => 3,
                'supervisiorId' => 1,
                'type' => 'main',
 
            ],]);

    }
}

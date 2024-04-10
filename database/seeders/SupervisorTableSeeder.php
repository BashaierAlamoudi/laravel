<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupervisorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('supervisior')->insert([
            [
                'supervisiorId' => 1,
                'firstName' => 'waffa',
                'middletName' => 'A.',
                'lastName' => 'super',
                'phone_number' => '1234567890',
                'email' => 'john.doe@example.com',
                'department' => '',
            ],]);
    }
}

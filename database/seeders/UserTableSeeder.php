<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'loginId' => 2005066,
                'password' => 'aa', 
                'firstName' => 'John',
                'middletName' => 'A.',
                'lastName' => 'Doe',
                'phone_number' => '1234567890',
                'email' => 'john.doe@example.com',
                'department' => '',
                'role' => 'student',
            ],
            [
                'loginId' => 2005067,
                'password' => 'aa', 
                'firstName' => 'John',
                'middletName' => 'A.',
                'lastName' => 'Doe',
                'phone_number' => '1234567899',
                'email' => 'john.doe@example1.com',
                'department' => '',
                'role' => 'student',
            ],
            [
                'loginId' => 2005069,
                'password' => 'aa', 
                'firstName' => 'gadah',
                'middletName' => 'A.',
                'lastName' => 'almuaikel',
                'phone_number' => '123456789',
                'email' => '',
                'department' => '',
                'role' => 'coordinator',
            ],
            
        ]);
    }
}

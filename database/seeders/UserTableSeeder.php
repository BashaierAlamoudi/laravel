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
                'firstName' => 'abdullah',
                'middletName' => 'A.',
                'lastName' => 'almuaikel',
                'phone_number' => '1234567890',
                'email' => 'abdullahalmuaikel1@gmail.com',
                'gender'=> 'Male',
                'department' => 'IT',
                'role' => 'student',
            ],
            [
                'loginId' => 2005067,
                'password' => 'aa', 
                'firstName' => 'gadah',
                'middletName' => 'A.',
                'lastName' => 'almuaikel',
                'phone_number' => '1234567899',
                'email' => 'gadahAlmuaikel@gmail.com',
                'gender'=> 'Female',
                'department' => 'CS',
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
                'gender'=> 'Female',
                'department' => 'CS',
                'role' => 'coordinator',
            ],
            [
                'loginId' => 2005061,
                'password' => 'aa', 
                'firstName' => 'Bashaier',
                'middletName' => 'A.',
                'lastName' => 'alamouudi',
                'phone_number' => '123456759',
                'email' => 'Bashaier.alamouudi@gmail.com',
                'gender'=> 'Female',
                'department' => 'IS',
                'role' => 'student',
            ],
            
        ]);
    }
}

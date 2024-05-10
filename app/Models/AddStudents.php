<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddStudents extends Model
{
    use HasFactory;
    protected $fillable = [
        'studentId', 'password', 'firstName', 'middleName', 'lastName', 'enrollYear',
        'gpa', 'phone_number', 'email', 'department', 'section', 'nationalId',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class instructor extends Model
{
    use HasFactory;
    protected $fillable = [
        'instructorId',
        'firstName',
        'middleName',
        'lastName',
        'phone_number',
        'email',
        'department',
        'role',
    ];
    public function students()
    {
        return $this->belongsToMany(Student::class, 'supervise', 'supervisor_id', 'student_id');
    }
   
}

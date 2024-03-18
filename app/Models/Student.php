<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'studentId',
        'password',
        'firstName',
        'middleName',
        'lastName',
        'phone_number',
        'email',
        'dateOfBirth',
        'graduationDate',
        'withdrawSemester',
        'PostponedSemester',
        'status',
        'enrollYear',
        'department',
        'gpa',
    ];

    protected $primaryKey = 'studentId';
    public $incrementing = false;
    protected $keyType = 'integer';
    public function publications()
    {
        return $this->hasMany(Publication::class);
    }
    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }
    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'supervise', 'student_id', 'supervisor_id');
    }
    public function requests()
    {
        return $this->hasMany(Request::class);
    }
    public function exams()
{
    return $this->belongsToMany(Exam::class, 'taken_exam', 'student_id', 'exam_id');
}
    

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'student';
    protected $fillable = [
        'userId',
        'dateOfBirth',
        'graduationDate',
        'withdrawSemester',
        'postponedSemester',
        'status',
        'enrollYear',
        'gpa',
    ];

    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Optionally, you can define casts for specific attributes
    protected $casts = [
        'withdrawSemester' => 'integer',
        'postponedSemester' => 'integer',
        'gpa' => 'float',
    ];
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
public function user()
{
    return $this->belongsTo(User::class);
}


}

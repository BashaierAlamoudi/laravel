<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $fillable = ['student_id', 'semester_id','status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}


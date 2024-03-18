<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprehensive_Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'examName',
        'field',
        'date',
    ];
    public function students()
{
    return $this->belongsToMany(Student::class, 'taken_exam', 'exam_id', 'student_id');
}

}

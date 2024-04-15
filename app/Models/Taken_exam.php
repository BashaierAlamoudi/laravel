<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comprehensive_Exam;
class Taken_Exam extends Model
{
    use HasFactory;

    protected $table = 'taken_exam';
    protected $primaryKey = 'takenExamId';
    public $timestamps = false;
    protected $fillable = [
        'loginId', 'examId', 'oralScore', 'writtenScore',
    ];


    public function comprehensiveExam()
    {
        return $this->belongsTo(Comprehensive_Exam::class, 'examId', 'examId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'loginId', 'id');
    }
}
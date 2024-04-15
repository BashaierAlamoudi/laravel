<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comperhensive_Exam;


class Taken_exam extends Model
{
    use HasFactory;

    protected $table = 'taken_exam';
    protected $primaryKey = 'takenExamId';
    public $timestamps = false;
    protected $fillable = [
        'userId',
        'examId',
        'attempt',
        'oralScore',
        'writtenScore'
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function exam()
    {
        return $this->belongsTo(ComprehensiveExam::class, 'examId');
    }
    

}

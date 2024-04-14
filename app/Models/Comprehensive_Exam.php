<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Taken_Exam;
class Comprehensive_Exam extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $table = 'comprehensive_exam'; // Define the table name

    protected $primaryKey = 'examId'; // Define the primary key name

    protected $fillable = [
        'year', 'season', 'description', 'pdfPath',
    ];
<<<<<<< HEAD

=======
>>>>>>> 10441b4bb981ceeb3a9198188580f600fbce6786


    public function takenExams()
    {
        return $this->hasMany(Taken_Exam::class, 'examId', 'examId');
    }
}

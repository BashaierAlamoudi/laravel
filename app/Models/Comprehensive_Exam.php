<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprehensive_Exam extends Model
{
    use HasFactory;
    protected $table = 'comprehensive_exam'; // Define the table name

    protected $primaryKey = 'examId'; // Define the primary key name

    protected $fillable = [
        'examName',
        'field',
        'date',
    ];


}

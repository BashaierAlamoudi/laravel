<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    protected $fillable = [
        'title',
        'field',
        'date',
        'time',
        'studentId',
        'location',
        'description',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Define any relationships with other models here
}

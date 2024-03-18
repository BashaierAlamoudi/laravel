<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class request_model extends Model
{
    use HasFactory;
    protected $fillable = [
        'studentId',
        'approvalStatus',
        'requestName',
        'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentId', 'id');
    }
    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }
}

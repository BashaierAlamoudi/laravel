<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervise extends Model
{
    use HasFactory;
    protected $table = 'supervise';

    protected $fillable = [
        'superviseId', 
        'userId', 
        'supervisiorId', 
        'type',
    ];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisorId');
    }
}

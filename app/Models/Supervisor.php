<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;
    protected $table = 'supervisior';

    protected $fillable = [
        'supervisorId', 
        'firstName', 
        'middleName', 
        'lastName', 
        'phone_number', 
        'email', 
        'department', 
    ];
    public function students()
    {
        return $this->belongsToMany(Student::class, 'supervise', 'supervisorId', 'userId')
                    ->withPivot('type');
    }
    
}

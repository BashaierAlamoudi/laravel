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
        'middletName', 
        'lastName', 
        'phone_number', 
        'email', 
        'department', 
    ];
    public function students()
{
    return $this->belongsToMany(User::class, 'supervise', 'supervisiorId', 'userId')
                ->withPivot('type');
}
}

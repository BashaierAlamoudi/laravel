<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table = 'user';
   // protected $primaryKey = 'id';
        protected $fillable = [
        'loginId', 'password', 'firstName', 'middletName', 'lastName', 'phone_number', 'email', 'department', 'role'
    ];


    protected $hidden = [
        'password',
    ];
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function coordinators()
    {
        return $this->hasMany(Coordinator::class);
    }
    public function takenExams()
{
    return $this->hasMany(Taken_Exam::class, 'loginId', 'loginId');
}
}

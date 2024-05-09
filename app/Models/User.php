<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Model implements Authenticatable
{
    use HasFactory, AuthenticableTrait;

    protected $table = 'user';
    //protected $primaryKey= 'loginId';

    protected $fillable = [
        'loginId', 'password', 'firstName', 'middleName', 'lastName', 'phone_number', 'email', 'department', 'role','gender'
    ];

    protected $hidden = [
        'password',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'userId'); // Assuming userId is the foreign key
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

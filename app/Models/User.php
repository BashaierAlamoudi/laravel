<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table = 'user';
    protected $primaryKey = 'loginId';
        protected $fillable = [
        'loginId', 'password', 'firstName', 'middleName', 'lastName', 'phone_number', 'email', 'department', 'role'
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
}

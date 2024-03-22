<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Publications extends Model
{
    use HasFactory;

    // Specify the table if it's not the pluralized form of the model name
    protected $table = 'publication';

    // Attributes that are mass assignable
    protected $fillable = [
        'title', 'field', 'date', 'pdfPath', 'userId'
    ];
    public $timestamps = false;

    // Attributes to append to the model's array and JSON form
    protected $appends = ['studentName', 'loginId'];



    /**
     * Define the relationship to the User model.
     */
    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }

    /**
     * Accessor to append the student's full name as 'studentName'.
     */
    public function getLoginIdAttribute() {
        return $this->user ? $this->user->loginId : null;
        }
    public function getStudentNameAttribute() {
        return $this->user ? $this->user->firstName . ' ' . $this->user->lastName : null;
    }
}

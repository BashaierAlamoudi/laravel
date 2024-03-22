<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Seminars extends Model

{
    protected $table= 'seminar';
    protected $primaryKey = 'seminarId';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'field',
        'date',
        'time',
        'location',
        'description',
        'userId' // Keep 'loginId' in $fillable if it's a column in your table
    ];
    
    protected $appends = ['studentName','loginId'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    
    public function getLoginIdAttribute()
    {
        return $this->user ? $this->user->userId : null;
    }
    
    public function getStudentNameAttribute()
    {
        
        return $this->user ? $this->user->firstName . ' ' . $this->user->lastName : 'unknown';
    }
}
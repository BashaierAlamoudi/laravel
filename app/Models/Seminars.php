<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Seminars extends Model
{
    protected $table = 'seminar';
    protected $primaryKey = 'seminarId';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'field',
        'date',
        'time',
        'location',
        'type', 
        'userId',
        'Name' 
    ];
    
    // Remove the $appends property since it's not needed for userId
    
    public function user()
    {
        // Adjust the relationship based on the 'type' being 'student'
        if ($this->type === 'student') {
            return $this->belongsTo(User::class, 'userId');
        } else {
            // If the type is not 'student', return a dummy relationship object
            return $this->belongsTo(User::class, 'userId')->whereNull('id');
        }
    }
    
    public function getNameAttribute($value)
    {
        return ucfirst($value); // Example: capitalize the name
    }
}
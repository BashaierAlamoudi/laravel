<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarAttendance extends Model
{
    protected $table = 'seminar_attendances'; // Ensure Laravel uses the correct table name

    protected $fillable = [
        'user_id', 'semester', 'title', 'date', 'certificate'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}

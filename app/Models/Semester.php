<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    protected $table = 'semesters';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['name', 'start_date', 'end_date'];


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    // using to calcalute the progress 
    public function isCurrent()
{
    $today = now(); // Or Carbon::now() if you use Carbon
    return $today->between($this->start_date, $this->end_date);
}
}

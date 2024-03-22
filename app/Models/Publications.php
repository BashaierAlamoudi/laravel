<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Supervisor;
use App\Models\Supervise;
class Publications extends Model
{
    use HasFactory;

    // Specify the table if it's not the pluralized form of the model name
 protected $table = 'publication';
 protected $primaryKey = 'publicationId';
 // Attributes that are mass assignable
 protected $fillable = [
     'title', 'field', 'date', 'pdfPath', 'userId'
 ];
 public $timestamps = false;

 // Attributes to append to the model's array and JSON form
 protected $appends = ['studentName', 'loginId', 'supervisorName'];

 /**
  * Define the relationship to the User model.
  */
 public function user()
 {
     return $this->belongsTo(User::class, 'userId');
 }

 /**
  * Define the relationship to the Supervisor model through the Supervise pivot table.
  * Assuming there's only one supervisor for each publication and their type is 'main'.
  */
 public function supervisors()
 {
     return $this->hasOneThrough(
         Supervisor::class, // Target model
         Supervise::class,  // Intermediate model
         'userId',          // Foreign key on intermediate model
         'id',              // Foreign key on target model
         'userId',          // Local key on publications table
         'supervisiorId'    // Local key on supervise table
     )->where('type', 'main');
 }

 /**
  * Accessor to append the student's full name as 'studentName'.
  */
 public function getLoginIdAttribute()
 {
     return $this->user ? $this->user->loginId : null;
 }

 /**
  * Accessor to append the supervisor's full name as 'supervisorName'.
  */
 public function getSupervisorNameAttribute()
 {
     return $this->supervisors ? $this->supervisors->firstName . ' ' . $this->supervisors->lastName : null;
 }

 public function getStudentNameAttribute()
 {
     return $this->user ? $this->user->firstName . ' ' . $this->user->lastName : null;
 }
}

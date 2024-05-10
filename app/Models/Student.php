<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'student';
    protected $guarded = [
    ];

    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    // Optionally, you can define casts for specific attributes
    protected $casts = [
        'withdrawSemester' => 'integer',
        'postponedSemester' => 'integer',
        'gpa' => 'float',
    ];
    public function publications()
    {
        return $this->hasMany(Publication::class);
    }
    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }
    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'supervise', 'student_id', 'supervisor_id');
    }
    public function requests()
    {
        return $this->hasMany(Request::class);
    }
    public function exams()
{
    return $this->belongsToMany(Exam::class, 'taken_exam', 'student_id', 'exam_id');
}
public function user()
{
    return $this->belongsTo(User::class);
}
public function markUnreadNotificationsAsRead()
{
    // Get all unread notifications for the user
    $unreadNotifications = $this->unreadNotifications;

    // Mark each unread notification as read
    $unreadNotifications->each(function (DatabaseNotification $notification) {
       // $notification->markAsRead();
    });

    // Return the unread notifications
    return $unreadNotifications;
}

public function calculateExpectedGraduationYear()
    {
        // Assuming each academic year has 2 semesters.
        $semestersPerYear = 2;
        $semesters_total = 4;
        // Calculate remaining semesters.
        $remainingSemesters = $semesters_total - $this->numberOfSemesters;

        // Calculate how many years are left.
        $yearsRemaining = ceil($remainingSemesters / $semestersPerYear);

        // Get the current year.
        $currentYear = now()->year;

        // Calculate the expected graduation year.
        $expectedGraduationYear = $currentYear + $yearsRemaining;

        return $expectedGraduationYear;
    }
    
    public function getUserName()
    {
        // Using the 'optional' to avoid errors if the user is null
        return optional($this->user)->name;
    }

}

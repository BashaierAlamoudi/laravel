<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Notifications\DatabaseNotification;
class User extends Model implements Authenticatable
{
    use HasFactory, AuthenticableTrait;

    protected $table = 'user';
    //protected $primaryKey= 'loginId';

    protected $fillable = [
        'loginId', 'password', 'firstName', 'middleName', 'lastName', 'phone_number', 'email','gender', 'department', 'role'
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
public function student()
{
    return $this->hasOne(Student::class, 'userId');
}
public function supervisors()
{
    return $this->belongsToMany(Supervisor::class, 'supervise', 'userId', 'supervisiorId')
                ->withPivot('type');  // Including type data
}
public function markUnreadNotificationsAsRead()
    {
        // Get all unread notifications for the user
        $unreadNotifications = $this->unreadNotifications;

        // Mark each unread notification as read
        $unreadNotifications->each(function (DatabaseNotification $notification) {
            $notification->markAsRead();
        });

        // Return the unread notifications
        return $unreadNotifications;
    }
    
}

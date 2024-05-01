<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class New_Student extends Model
{
    use HasFactory;
    protected $table = 'new_students';
    protected $primaryKey = 'userId';
    
   public $timestamps = false;
    protected $fillable = 
    ['userId','nationalId','password',
    'firstName', 'middletName','lastName',
    'enrollYear',  'gpa','phone_number',
     'email', 'department','section', ];
     // 'middletName','lastName','enrollYear',  'gpa','phone_number',
    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }
}

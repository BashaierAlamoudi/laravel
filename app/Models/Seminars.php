<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seminars extends Model
{
    public $timestamps = false;
    protected $table = 'seminars';


    protected $fillable = ['studentId', 'studentName', 'title', 'field', 'location','date'];

}

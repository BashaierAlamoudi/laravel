<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publications extends Model
{
    public $timestamps = false;
    protected $table = 'publications';
    protected $primaryKey = 'studentId';


    protected $fillable = ['studentId', 'studentName', 'supervisorName', 'title', 'field','date','pdfPath'];

}

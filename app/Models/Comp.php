<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comp extends Model
{
    public $timestamps = false;
    protected $table = 'comp';


    protected $fillable = ['studentName', 'numAttempts', 'examName', 'score', 'date'];

    // Define any relationships here, if needed
}

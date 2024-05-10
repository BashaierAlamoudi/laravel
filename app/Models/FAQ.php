<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    protected $table = 'faqs'; // Specify the table name here

    protected $fillable = ['question', 'answer']; // Define the fillable attributes

}

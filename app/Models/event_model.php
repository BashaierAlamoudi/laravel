<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event_model extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'eventDate',
        'description',
    ];
    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }
}

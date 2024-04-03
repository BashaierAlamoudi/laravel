<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event_model extends Model
{    
    protected $table = 'event_table';
    protected $primaryKey = 'eventId';
    use HasFactory;
    
   public $timestamps = false;
    protected $fillable = [
        'title',
        'eventStart',
        'eventEnd',
        'description',
    ];
    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }
}


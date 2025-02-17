<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'room_id', 'message', 'is_read'];

    // public function teacher()
    // {
    //     return $this->belongsTo(Teacher::class);
    // }

    public function room()
    {
        return $this->belongsTo(Rooms::class);
    }
}

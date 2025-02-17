<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRoom extends Model
{
    use HasFactory;

    protected $table = 'student_rooms';

    protected $fillable = [
        'folder_id',
        'room_id',
        'student_id',
        'student_name',
        'teacher_name',
        'class_name',
        'subject',
        'section'
    ];

    public function rooms(): BelongsTo {
        return $this->belongsTo(Rooms::class);
    }

}

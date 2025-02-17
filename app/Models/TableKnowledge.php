<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableKnowledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'room_id',
        'student_name',
        'row', 
        'column', 
        'content'
    ];
}

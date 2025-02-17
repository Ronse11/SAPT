<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSkills extends Model
{
    use HasFactory;

    protected $table = 'skills';
    // protected $primaryKey = 'id';
    // public $incrementing = false; 


    protected $fillable = [
        'id',
        'teacher_id',
        'room_id',
        // 'student_room_id',
        'student_name',
        'row', 
        'column', 
        'content',
        'merged',
        'rowspan',
        'colspan',
        'formula'
    ];
}

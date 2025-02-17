<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableMerge extends Model
{
    use HasFactory;

    protected $table = 'table_merge';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'student_name',
        'row', 
        'column', 
        'content',
        'merged',
        'rowspan',
        'colspan'
    ];
}

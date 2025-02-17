<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableBorderAllCell extends Model
{
    use HasFactory;

    protected $table = 'table_borders_all_cell';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'row', 
        'column', 
        'isTop',
        'isBottom',
        'isLeft',
        'isRight'
    ];
}

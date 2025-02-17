<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableColor extends Model
{
    use HasFactory;

    protected $table = 'table_colors';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'row', 
        'column',
        'color_name',
        'type'
    ];
}

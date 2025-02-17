<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSelectedRowCol extends Model
{
    use HasFactory;

    protected $table = 'table_selected_row_col';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'row', 
        'column', 
    ];
}

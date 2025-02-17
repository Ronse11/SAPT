<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableTotalRowCol extends Model
{
    use HasFactory;

    protected $table = 'rows_and_cols';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'total_row', 
        'total_column', 
    ];
}

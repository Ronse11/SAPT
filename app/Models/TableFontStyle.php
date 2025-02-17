<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableFontStyle extends Model
{
    use HasFactory;

    protected $table = 'table_font_style';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'row', 
        'column',
        'style'
    ];
}

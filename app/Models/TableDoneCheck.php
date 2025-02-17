<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableDoneCheck extends Model
{
    use HasFactory;

    protected $table = 'table_done_check';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'column', 
    ];
}

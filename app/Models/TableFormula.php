<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableFormula extends Model
{
    use HasFactory;

    protected $table = 'table_formula';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'column',
        'formula'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingSheet extends Model
{
    use HasFactory;

    protected $table = 'table_ratings_sheet';

    protected $fillable = [
        'table_id',
        'teacher_id',
        'room_id',
        'column', 
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNames extends Model
{
    use HasFactory;

    protected $table = 'student_names';

    protected $fillable = [
        'room_id',
        'name_1',
        'name_2',
        'name_3',
    ];

}

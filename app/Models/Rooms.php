<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rooms extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'teacher_id',
        'teacher_name',
        'class_name',
        'subject',
        'section',
        'room_code'
    ];

    public function studentRoom(): HasMany {
        return $this->hasMany(StudentRoom::class);
    }

    public function folders(): BelongsTo {
        return $this->belongsTo(Folders::class);
    }

}

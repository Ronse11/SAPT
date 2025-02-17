<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folders extends Model
{
    use HasFactory;

    protected $table = 'folders';

    protected $fillable = [
        'role',
        'user_id',
        'folder_name',
    ];

    public function room(): HasMany {
        return $this->hasMany(Rooms::class);
    }
}

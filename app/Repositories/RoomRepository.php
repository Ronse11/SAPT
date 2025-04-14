<?php
namespace App\Repositories;

use App\Models\Rooms;
use App\Models\Folders;

class RoomRepository
{
    public function getRoomsForTeacher($userId, $folderId = null)
    {
        $query = Rooms::orderBy('created_at', 'desc')
            ->where('teacher_id', $userId);

        if ($folderId !== null) {
            $query->where('folder_id', $folderId);
        }

        return $query->get();
    }

    public function getFoldersForTeacher($userId)
    {
        return Folders::where('user_id', $userId)->where('role', 'Teacher')->get();
    }

    public function getFirstFolderForTeacher($userId)
    {
        return Folders::where('user_id', $userId)->where('role', 'Teacher')->first();
    }

    public function getClassCountForTeacher($userId)
    {
        return Rooms::where('teacher_id', $userId)->count();
    }

    public function getFolderCountForTeacher($userId)
    {
        return Folders::where('user_id', $userId)->where('role', 'Teacher')->count();
    }
}

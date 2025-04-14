<?php
namespace App\Repositories;

use App\Models\Folders;
use App\Models\StudentRoom;

class RoomStudentRepository
{
    public function getRoomsForStudent($userId, $folderId = null) 
    {
        $query = StudentRoom::orderBy('created_at', 'desc')
                ->where('student_id', $userId);

        if ($folderId !== null) {
            $query->where('folder_id', $folderId);
        }

        return $query->get();
    }

    public function getFoldersForStudent($userId)
    {
        return Folders::orderBy('created_at', 'desc')->where('user_id', $userId)->where('role', 'Student')->get();
    }

    public function getFirstFoldersForStudent($userId)
    {
        return Folders::where('user_id', $userId)->where('role', 'Student')->first();
    }

    public function getClassCountForStudent($userId)
    {
        return StudentRoom::where('student_id', $userId)->count();
    }

    public function getFolderCountForStudent($userId)
    {
        return Folders::where('user_id', $userId)->where('role', 'Student')->count();
    }

}
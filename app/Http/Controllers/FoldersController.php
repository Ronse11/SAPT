<?php

namespace App\Http\Controllers;

use App\Models\Folders;
use App\Models\Rooms;
use App\Models\StudentRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoldersController extends Controller
{
    public function index()
    {
        return view('choices.createFolder');
    }

    public function create()
    {
        return view('folders.create');
    }

    public function store(Request $request)
    {
        $userID = Auth::id();
        $userRole = User::where('id', $userID)->first();

        $request->validate(['folder_name' => 'required']);

        Folders::create([
            'user_id' => $userID,
            'role' => $userRole->role,
            'folder_name' => $request->input('folder_name'),
        ]);

        if($userRole->role === 'Teacher') {
            return redirect()->route('teacher-home');
        } else {
            return redirect()->route('student-home');
        }

    }

    public function teacherFolderShow($id)
    {
        $userId = Auth::id();
        $rooms = Rooms::orderBy('created_at', 'desc')->where('teacher_id', $userId)->where('folder_id', $id)->get();
        $allRooms = Rooms::orderBy('created_at', 'desc')->where('teacher_id', $userId)->get();
        $classCount = Rooms::where('teacher_id', $userId)->count();
        $folderCount = Folders::where('user_id', $userId)->count();

        $folders = Folders::where('user_id', $userId)->get();

        return view('folders.teacherFolders', ['rooms' => $rooms, 'folders' => $folders, 'classCount' => $classCount, 'folderCount' => $folderCount, 'allRooms' => $allRooms, 'folder_id' => $id]);
    }

    public function studentFolderShow($id)
    {
        $userId = Auth::id();
        $rooms = StudentRoom::orderBy('created_at', 'desc')->where('student_id', $userId)->where('folder_id', $id)->get();
        $allRooms = StudentRoom::orderBy('created_at', 'desc')->where('student_id', $userId)->get();
        $classCount = StudentRoom::where('student_id', $userId)->count();
        $folderCount = Folders::where('user_id', $userId)->count();

        $folders = Folders::where('user_id', $userId)->get();

        return view('folders.studentFolders', ['rooms' => $rooms, 'folders' => $folders, 'classCount' => $classCount, 'folderCount' => $folderCount, 'allRooms' => $allRooms, 'folder_id' => $id]);
    }
}

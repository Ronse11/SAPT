<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperFunctions;

use App\Models\Folders;
use App\Models\Invitations;
use App\Models\Rooms;
use App\Models\StudentNames;
use App\Models\StudentRoom;
use App\Models\TableAttitude;
use App\Models\TableKnowledge;
use App\Models\TableRatings;
use App\Models\TableSkills;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\DataTransferObjects\TeacherHomeData;
use App\Helpers\RoomHelper;
use App\Models\Notification;
use App\Repositories\RoomRepository;


class RoomController extends Controller
{

    protected $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    // Generate Code
    public function generateUniqueCode()
    {
        // Will be the source of Code
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        do {
            $randomCode = '';
            // Generate random code in the format $$$-$$$-$$$
            for ($i = 0; $i < 11; $i++) {
                if ($i == 3 || $i == 7) {
                    $randomCode .= '-';
                } else {
                    $randomCode .= $characters[rand(0, $charactersLength - 1)];
                }
            }
        } while (Rooms::where('room_code', $randomCode)->exists());

        return $randomCode;
    }

    public function showCodeForm()
    {
        return view('choices/create');
    }

    public function showFolderForm($id)
    {
        $userId = Auth::id();
        $folderId = Folders::where('user_id', $userId)->where('id', $id)->first();

        return view('choices/createFolderRoom', compact('folderId'));
    }

    // Storing Room with 0 folder_id
    public function storeRoom(Request $request) {

        $userId = Auth::id();
        $roomCode = $this->generateUniqueCode();
        $teacher = User::where('id', $userId)->first();

        $token = Str::random(32);
        $expiration = now()->addDays(7);

        $validated = $request->validate([
            // 'folderId' => ['nullable', 'integer'],
            'class_name' => ['required'],
            'subject' => ['required'],
            'section' => ['required']
        ]);
            
            $room = Rooms::create([
                // 'folder_id' => 1,
                'teacher_id' => $userId,
                'teacher_name' => $teacher->username,
                'class_name' => $validated['class_name'],
                'subject' => $validated['subject'],
                'section' => $validated['section'],
                'room_code' => $roomCode,
            ]);

            Invitations::create([
                'teacher_id' => $userId,
                'classroom_id' => $room->id,
                'token' => $token,
                'expires_at' => $expiration,
            ]);

            $user = User::find($userId);

            $user->role = $request->input('role');
            $user->save();

            $shareableLink = route('share', ['token' => $token]);

        return redirect()->route('teacher-home')->with(['roomCreated' => true, 'shareableLink' => $shareableLink]);
    }

    // Storing Room with 1UP folder_id
    public function storeRoomFolder(Request $request, $id) {

        $userId = Auth::id();
        $roomCode = $this->generateUniqueCode();
        $teacher = User::where('id', $userId)->first();
        // $teacherName = Rooms::where('room_code', $userId)->first();
    
        $validated = $request->validate([
            'class_name' => ['required'],
            'subject' => ['required'],
            'section' => ['required']
        ]);

            Rooms::create([
                'folder_id' => $id,
                'teacher_id' => $userId,
                'teacher_name' => $teacher->username,
                'class_name' => $validated['class_name'],
                'subject' => $validated['subject'],
                'section' => $validated['section'],
                'room_code' => $roomCode,
            ]);
    
            $user = User::find($userId);
    
            $user->role = $request->input('role');
            $user->save();
    
        return redirect()->route('teacher-home')->with('roomCreated', true);
                
    }

    public function show() {
        $userId = Auth::id();

        $rooms = $this->roomRepository->getRoomsForTeacher($userId, 0);
        $allRooms = $this->roomRepository->getRoomsForTeacher($userId);
        $folderRooms = $this->roomRepository->getRoomsForTeacher($userId);
        $folders = $this->roomRepository->getFoldersForTeacher($userId);
        $folder = $this->roomRepository->getFirstFolderForTeacher($userId);
        $classCount = $this->roomRepository->getClassCountForTeacher($userId);
        $folderCount = $this->roomRepository->getFolderCountForTeacher($userId);

        $roomsWithUrls = $rooms->map(function ($room) {
            $room->encoded_url = RoomHelper::generateRoomUrl($room);
            return $room;
        });

        $data = new TeacherHomeData(
            $rooms,
            $roomsWithUrls,
            $folders,
            $folder,
            $folderRooms,
            $allRooms,
            $classCount,
            $folderCount
        );

        return view('teacher.teacherHome', compact('data'));
    }
    

    public function showRecord($id) {

        $userId = Auth::id();
        $decodeID = HelperFunctions::base64_url_decode($id);

        $allRooms = $this->roomRepository->getRoomsForTeacher($userId);
        $folderRooms = $this->roomRepository->getRoomsForTeacher($userId);
        $folders = $this->roomRepository->getFoldersForTeacher($userId);
        $records = Rooms::where('id', $decodeID)->first();
        $count = StudentNames::where('room_id', $decodeID)->count();
        
        $encodedId = HelperFunctions::base64_url_encode($records->id);

        $notifications = Notification::where('teacher_id', $userId)
        ->where('room_id', $decodeID)
        ->orderBy('created_at', 'desc')
        ->get();

        $readNotifications = $notifications->where('is_read', false)->count();
        
        return view('users.teacher', ['encodedId' => $encodedId, 'count' => $count, 'encodedId' => $encodedId, 'folderRooms' => $folderRooms, 'allRooms' => $allRooms, 'folders' => $folders, 'notifications' => $notifications, 'readNotifications' => $readNotifications]);
    }

    public function settings($id) {

        $userId = Auth::id();
        $decyptedRoomID = HelperFunctions::base64_url_decode($id);

        $records = Rooms::where('id', $decyptedRoomID)->first();
        $room = Rooms::where('id', $decyptedRoomID)->where('teacher_id', $userId)->first();
        $encodedID = HelperFunctions::base64_url_encode($room->id);

        $inviteLink = Invitations::where('classroom_id', $decyptedRoomID)->first();
        // $role = User::where('id', $id)->first();
        $role = $records ? User::where('id', $records->teacher_id)->first() : null;

        $shareableLink = $inviteLink ? route('share', ['token' => $inviteLink->token]) : null;

        return view('records.setting', ['record' => $records,'encodedID' => $encodedID, 'role' => $role, 'shareableLink' => $shareableLink]);
    }

    // Teacher Settings
    public function teacherSettings() {

        $userId = Auth::id();

        $allRooms = $this->roomRepository->getRoomsForTeacher($userId);
        $folders = $this->roomRepository->getFoldersForTeacher($userId);

        $user = User::where('id', $userId)->first();
        $userName = $user->school_name;

        return view('teacher.setting', ['allRooms' => $allRooms, 'folders' => $folders, 'userName' => $userName]);
    }    
    // Student Calendar
    public function teacherCalendar() {

        $userId = Auth::id();

        $allRooms = $this->roomRepository->getRoomsForTeacher($userId);
        $folders = $this->roomRepository->getFoldersForTeacher($userId);

        return view('teacher.calendar', ['allRooms' => $allRooms, 'folders' => $folders]);
    }

    // Update the Class Information
    public function updateClass(Request $request, $id) {
        $teacherClass = Rooms::where('id', $id)->first();
        $studentClass = StudentRoom::where('room_id', $id);

        // $updateStudentClass = $studentClass->room_id;
    
        $attributes = ['class_name', 'subject', 'section'];
        foreach ($attributes as $attribute) {
            if ($request->has($attribute)) {
                $teacherClass->update([$attribute => $request->$attribute]);
                $studentClass->update([$attribute => $request->$attribute]);
            }
        }
    
        return response()->json(['success' => true]);
    }

    // DELETION OF ROOM
    public function destroyRoom($id)
    {
        // Find the item by ID
        $room = Rooms::findOrFail($id);

        if($room) {
            $room->delete();
        }
        
        return redirect()->route('teacher-home')->with('roomDeleted', true);
    }

    // DELETION OF FOLDER
    public function destroyFolder($id)
    {
        // Find the item by ID
        $folder = Folders::findOrFail($id);

        if($folder) {
            $folder->delete();
        }
        
        return redirect()->route('teacher-home')->with('roomDeleted', true);
    }

    public function destroyStudentName(Request $request)
    {
        try {
            $ids = $request->input('ids');
    
            if ($ids) {
                // Perform deletion
                $deleted = StudentNames::whereIn('id', $ids)->delete();
    
                // Log the result of the deletion
                Log::info('Deletion result', ['deleted_count' => $deleted]);
    
                if ($deleted) {
                    $message = count($ids) > 1 
                        ? 'Selected items deleted successfully.' 
                        : 'Selected item deleted successfully.';
    
                    return redirect()->back()->with('success', $message);
                } else {
                    return redirect()->back()->with('error', 'No records were deleted.');
                }
            } else {
                return redirect()->back()->with('error', 'No items selected');
            }
        } catch (\Exception $e) {
            // Log the exception
            return redirect()->back()->with('error', 'An error occurred while deleting the items.');
        }
    }

    public function deleteReadNotifications($id)
{
    $teacher = User::where('id', $id)->first();

    if ($teacher) {
        // Delete only notifications that have been read
        $teacher->notifications()->delete();
    }

    return back()->with('success', 'All read notifications deleted.');
}


    
}

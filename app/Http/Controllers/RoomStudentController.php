<?php

namespace App\Http\Controllers;

use App\Helpers\RoomHelper;
use App\Models\Folders;
use App\Models\Invitations;
use App\Models\Rooms;
use App\Models\StudentNames;
use App\Models\StudentRoom;
use App\Models\TableAttendance;
use App\Models\TableDoneCheck;
use App\Models\TableSelectedRowCol;
use App\Models\TableSkills;
use App\Models\User;
use App\Notification\StudentJoinedNotification;
use App\Repositories\RoomStudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoomStudentController extends Controller
{
    protected $roomStudentRepository;

    public function __construct(RoomStudentRepository $roomStudentRepository)
    {
        $this->roomStudentRepository = $roomStudentRepository;
    }


    public function checkCode(Request $request) {

        $userId = Auth::id();

        $room = Rooms::where('room_code', $request->input('code_number'))->first();
        $user = User::where('id', $userId)->first();

        if ($room) {
            $studentID = Auth::id();
            
            $access = StudentRoom::where('room_id', $room->id)->where('student_id', $studentID)->first();
            
            if (!$access) {
                StudentRoom::create([
                    'room_id' => $room->id,
                    'student_id' => $studentID,
                    'student_name' => $user->school_name,
                    'teacher_name' => $room->teacher_name,
                    'class_name' => $room->class_name,
                    'subject' => $room->subject,
                    'section' => $room->section,
                ]);

                $user = User::find($studentID);

                $user->role = $request->input('role');
                $user->save();

                $teacher = User::where('id', $room->teacher_id)->first(); // Ensure teacher_id exists in Rooms

                if ($teacher) {        
                    $notification = new StudentJoinedNotification($user, $room);
                    $notification->send($teacher);
                }
            }

            if(!$user->phone_number) {
                return redirect()->route('phoneNumber');
            }
            
            return redirect()->route('student-home');
            
        } else {
            return redirect()->route('track')->withErrors(['error' => 'Incorrect Track Code']);
        }
    }

    public function showPhone() {
        return view('choices/phoneNumber');
    }

    public function checkPhoneNumber(Request $request) {
        $userID = Auth::id();
        $currentUser = User::where('id', $userID)->first();

        if(!$currentUser->phone_number) {
            $user = User::find($userID);

            $user->phone_number = $request->input('phone_number');
            $user->save();

            return redirect()->route('student-home');

        }
    }

    // Joined Class
    public function joinedCLass(Request $request, $id) {
        $userId = Auth::id();
        $encodedId = HelperFunctions::base64_url_encode($id);

        $roomJoined = Rooms::where('id', $id)->first();
        $user = User::where('id', $userId)->first();

        if($roomJoined) {
            $accepted = StudentRoom::where('room_id', $roomJoined->id)->where('student_id', $userId)->first();

            if(!$accepted) {
                StudentRoom::create([
                    'room_id' => $roomJoined->id,
                    'student_id' => $userId,
                    'student_name' => $user->school_name,
                    'teacher_name' => $roomJoined->teacher_name,
                    'class_name' => $roomJoined->class_name,
                    'subject' => $roomJoined->subject,
                    'section' => $roomJoined->section,
                ]);

                $user = User::find($userId);

                $user->role = 'Student';
                $user->save();

                $teacher = User::where('id', $roomJoined->teacher_id)->first(); // Ensure teacher_id exists in Rooms

                if ($teacher) {        
                    $notification = new StudentJoinedNotification($user, $roomJoined);
                    $notification->send($teacher);
                }
            } 

            if(!$user->phone_number) {
                return redirect()->route('phoneNumber');
            }

            return redirect()->route('student.room', $encodedId);
            
        } else {
            return redirect()->route('track')->withErrors(['error' => 'Incorrect Track Code']);
        }
    }

    public function show() {

        $userId = Auth::id();

        $rooms = $this->roomStudentRepository->getRoomsForStudent($userId);
        $allRooms = $this->roomStudentRepository->getRoomsForStudent($userId);
        $folderRooms = $this->roomStudentRepository->getRoomsForStudent($userId);
        $classCount = $this->roomStudentRepository->getClassCountForStudent($userId);
        $folderCount = $this->roomStudentRepository->getFolderCountForStudent($userId);
        $folders = $this->roomStudentRepository->getFoldersForStudent($userId);
        $folder = $this->roomStudentRepository->getFirstFoldersForStudent($userId);

        $roomsWithUrls = $rooms->map(function ($room) {
            $room->encoded_url = RoomHelper::generateRoomStudentUrl($room);
            return $room;
        });

        return view('student.studentHome', ['student_room' => $rooms, 'folders' => $folders, 'folder' => $folder, 'folderRooms' => $folderRooms, 'allRooms' => $allRooms, 'classCount' => $classCount, 'folderCount' => $folderCount, 'roomsWithUrls' => $roomsWithUrls]);

    }

    // SHOW LINK PAGE
    public function showLink($token)
    {
        // Find the invitation by token and check if it's still valid
        $invitation = Invitations::where('token', $token)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$invitation) {
            return redirect('/')->with('error', 'This invitation link is invalid or has expired.');
        }

        $roomData = Rooms::where('id', $invitation->classroom_id)->first();
        // $encodedId = HelperFunctions::base64_url_encode($roomData->id);

        $userId = Auth::id();

        $rooms = $this->roomStudentRepository->getRoomsForStudent($userId);
        $allRooms = $this->roomStudentRepository->getRoomsForStudent($userId);
        $folderRooms = $this->roomStudentRepository->getRoomsForStudent($userId);
        $classCount = $this->roomStudentRepository->getClassCountForStudent($userId);
        $folderCount = $this->roomStudentRepository->getFolderCountForStudent($userId);
        $folders = $this->roomStudentRepository->getFoldersForStudent($userId);

        $folder = $this->roomStudentRepository->getFirstFoldersForStudent($userId);

        return view('link.linkPage', ['student_room' => $rooms, 'folders' => $folders, 'folder' => $folder, 'folderRooms' => $folderRooms, 'allRooms' => $allRooms, 'classCount' => $classCount, 'folderCount' => $folderCount, 'roomData' => $roomData]);
    }


    // SHOW STUDENT RECORD
    public function showRoom(Request $request, $id) {

        $userId = Auth::id();
        $tableId = $request->input('key');
        $decodeID = HelperFunctions::base64_url_decode($id);
        $encodedId = HelperFunctions::base64_url_encode($decodeID);

        $student = User::where('id', $userId)->first();
        $room = StudentRoom::where('room_id', $decodeID)->first();

        $skills = TableSkills::where('room_id', $decodeID)
        ->whereRaw('TRIM(student_name) = ?', [trim($student->school_name)])
        ->get();

    
        $roomID = TableSkills::where('room_id', $decodeID)->get();

        $getStartingRow = TableSelectedRowCol::where('table_id', $tableId)->where('room_id', $decodeID)->first();
        
        if(!$getStartingRow) {
            return view('studentRecords.noRow', ['encodedId' => $encodedId]);
        }

        $startingRow = $getStartingRow->row;
        $startRow = TableSkills::where('room_id', $decodeID)->where('row', '<', $startingRow)->orderBy('row', 'asc')->get();

        $getRowChecked = TableDoneCheck::where('table_id', $tableId)->where('room_id', $decodeID)->get();
        $doneCheck = [];
        foreach($getRowChecked as $checked) {
            $doneCheck[] = $checked->column;
        }
        
        $nameInUser = $student->school_name;
        $getNames = StudentNames::where('room_id', $decodeID)->where('name_3', $nameInUser)->first();

        return view('studentRecords.studentRecord', ['skills' => $skills, 'encodedId' => $encodedId, 'startRow' => $startRow, 'data' => $roomID, 'room' => $room, 'nameAsStudent' => $getNames, 'student' => $student, 'id' => $id, 'doneCheck' => $doneCheck]);
    }

    // SHOW STUDENT ATTENDANCE
    public function showAttendance(Request $request, $id) {

        $userId = Auth::id();
        $tableId = $request->input('key');
        $decodeId = HelperFunctions::base64_url_decode($id);
        $encodedId = HelperFunctions::base64_url_encode($decodeId);

        $student = User::where('id', $userId)->first();
        $room = StudentRoom::where('room_id', $id)->first();

        $skills = TableAttendance::where('room_id', $decodeId)
        ->whereRaw('TRIM(student_name) = ?', [trim($student->school_name)])
        ->get();

        $roomID = TableAttendance::where('room_id', $id)->get();

        $getStartingRow = TableSelectedRowCol::where('table_id', $tableId)->where('room_id', $decodeId)->first();
        
        if(!$getStartingRow) {
            return view('studentRecords.noRow', ['encodedId' => $encodedId]);
        }

        $startingRow = $getStartingRow->row;
        $startRow = TableAttendance::where('room_id', $decodeId)->where('row', '<', $startingRow)->orderBy('row', 'asc')->get();

        $getRowChecked = TableDoneCheck::where('table_id', $tableId)->where('room_id', $decodeId)->get();
        $doneCheck = [];
        foreach($getRowChecked as $checked) {
            $doneCheck[] = $checked->column;
        }

        $nameInUser = $student->school_name;
        $getNames = StudentNames::where('room_id', $decodeId)->where('name_3', $nameInUser)->first();

        return view('studentRecords.studentAttendance', ['skills' => $skills, 'startRow' => $startRow, 'encodedId' => $encodedId, 'data' => $roomID, 'room' => $room, 'nameAsStudent' => $getNames, 'doneCheck' => $doneCheck]);
    }

    // Show Student Record and Attendance
    public function showRecord($id) {

        $userId = Auth::id();
        $decodeID = HelperFunctions::base64_url_decode($id);

        $student = StudentRoom::where('student_id', $userId)->first();
        $records = StudentRoom::where('room_id', $decodeID)->first();

        $allRooms = StudentRoom::orderBy('created_at', 'desc')->where('student_id', $userId)->get();
        $folderRooms = StudentRoom::orderBy('created_at', 'desc')->where('student_id', $userId)->get();
        $folders = Folders::where('user_id', $userId)->get();

        // $count = StudentRoom::where('room_id', $id)->count();

        // $rooms = StudentRoom::where('room_id', $id)->where('student_id', $userId)->firstOrFail();
        // $knowledge = TableKnowledge::where('room_id', $id)->where('student_name', $student->student_name)->get();
        $skills = TableSkills::where('room_id', $decodeID)->where('student_name', $student->student_name)->get();

        $roomID = TableSkills::where('room_id', $decodeID)->get();

        $encodedId = HelperFunctions::base64_url_encode($records->room_id);


        // $row1 = TableSkills::where('room_id', $id)->where('row', 1)->get();
        // $row2 = TableSkills::where('room_id', $id)->where('row', 2)->get();
        // $row3 = TableSkills::where('room_id', $id)->where('row', 3)->get();
        // $row4 = TableSkills::where('room_id', $id)->where('row', 4)->get();
        // $row5 = TableSkills::where('room_id', $id)->where('row', 5)->get();

        // $columns = TableSkills::where('room_id', $id)->where('student_name', $student->student_name)->select('column', 'content')->get()->groupBy('column');


        // $attitude = TableAttitude::where('room_id', $id)->where('student_name', $student->student_name)->get();

        return view('users.student', ['skills' => $skills, 'encodedId' => $encodedId, 'data' => $roomID, 'record' => $records, 'allRooms' => $allRooms, 'folderRooms' => $folderRooms, 'folders' => $folders]);
        // return view('users.student', ['skills' => $skills, 'row1' => $row1, 'row2' => $row2, 'row3' => $row3, 'row4' => $row4, 'row5' => $row5, 'data' => $roomID]);
    }

    // DELETION OF STUDENT ROOM
    public function destroyRoom($id)
    {
        $room = StudentRoom::findOrFail($id);

        if($room) {
            $room->delete();
        }

        return redirect()->route('student-home')->with('roomDeleted', true);
    }

    // Form for Tracking Room in a Folder
    public function showFolderFormTrack($id)
    {
        $userId = Auth::id();
        $folderId = Folders::where('user_id', $userId)->where('id', $id)->first();

        return view('choices/trackFolderRoom', compact('folderId'));
    }

    // Store the the Tracked Room in a Folder
    public function storeTrackFolder(Request $request, $id) {

        $room = Rooms::where('room_code', $request->input('code_number'))->first();
        
        if ($room) {
            $studentID = Auth::id();
            
            $access = StudentRoom::where('room_id', $room->id)->where('student_id', $studentID)->first();

            $request->validate([
                'full_name' => [
                    'required',
                    'regex:/^[A-Z][a-z]+, [A-Z][a-z]+ [A-Z]\.( (II|III|ll|lll|Jr\.|Sr\.)?)?$/',
                ],
            ], [
                'full_name.regex' => 'The Fullname is gegemon'
            ]);
            
            if (!$access) {
                StudentRoom::create([
                    'folder_id' => $id,
                    'room_id' => $room->id,
                    'student_id' => $studentID,
                    'student_name' => $request->input('full_name'),
                    'teacher_name' => $room->teacher_name,
                    'class_name' => $room->class_name,
                    'subject' => $room->subject,
                    'section' => $room->section,
                ]);

                $user = User::find($studentID);

                $user->role = $request->input('role');
                $user->save();
            }
            
            return redirect()->route('student-home');
            
        } else {
            return redirect()->route('track')->withErrors(['error' => 'Incorrect Track Code']);
        }
    }

    // Student Settings
    public function studentSettings() {

        $userId = Auth::id();
        $allRooms = StudentRoom::orderBy('created_at', 'desc')->where('student_id', $userId)->get();

        $folders = Folders::where('user_id', $userId)->get();

        return view('student.setting', ['allRooms' => $allRooms, 'folders' => $folders]);
    }
    // Student Calendar
    public function studentCalendar() {

        $userId = Auth::id();
        $allRooms = StudentRoom::orderBy('created_at', 'desc')->where('student_id', $userId)->get();

        $folders = Folders::where('user_id', $userId)->get();

        return view('student.calendar', ['allRooms' => $allRooms, 'folders' => $folders]);
    }
}

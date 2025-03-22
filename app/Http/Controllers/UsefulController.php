<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperFunctions;

use App\Models\AttendanceTable;
use App\Models\RatingSheet;
use App\Models\Rooms;
use App\Models\StudentNames;
use App\Models\StudentRoom;
use App\Models\TableAttendance;
use App\Models\TableDoneCheck;
use App\Models\TableFormula;
use App\Models\TableMerge;
use App\Models\TableRatings;
use App\Models\TableSelectedRowCol;
use App\Models\TableSkills;
use App\Models\TableTotalRowCol;
use App\Models\User;
use App\Rules\SchoolNameMatchesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsefulController extends Controller
{



    // public function nextSequence($number) {
    //     $firstChar = chr(floor($number / 26) + ord('A'));
    //     $secondChar = chr(floor($number % 26) + ord('a'));
    //     $num =  $firstChar . $secondChar;
    //     return view('records.sample', ['number' => $num]);
    // }
    // private function nextSequence($index)
    // {
    //     if ($index < 26) {
    //         // A-Z
    //         return chr($index + ord('A'));
    //     } else {
    //         // Ab-Az
    //         $index -= 26;
    //         $firstChar = chr(floor($index / 26) + ord('A'));
    //         $secondChar = chr(($index % 26) + ord('A'));
    //         return $firstChar . $secondChar;
    //     }
    // }

    // private function generateSequences($count)
    // {
    //     $sequences = [];
    //     for ($i = 0; $i < $count; $i++) {
    //         $sequences[] = $this->nextSequence($i);
    //     }
    //     return $sequences;
    // }

    public function home(Request $request) {

        $userID = Auth::id();
        $studentName = User::where('id', $userID)->first();

        return view('navigations.home', compact('studentName'));
    }

    // Submitting Full Name
    public function fullName(Request $request) {

        $userID = Auth::id();
        $studentName = User::where('id', $userID)->first();

        $validatedFullName = $request->validate([
            'full_name' => [
                'required',
                'regex:/^[A-Z][a-z]+,\s?.*$/',
                new SchoolNameMatchesEmail($studentName->email)
            ],
        ], [
            'full_name.regex' => 'The Fullname you provided is invalid!'
        ]);

        $user = User::find($userID);

        $user->school_name = $validatedFullName['full_name'];
        $user->save();
        
        return redirect()->intended('/home');

    }

    // SEARCH FUNCTIONALITY HERE! FOR TEACHER SIDE!
    public function searchTeacher(Request $request)
    {
        $query = $request->get('query');
        $rooms = Rooms::orderBy('created_at', 'desc')->where('class_name', 'LIKE', "%{$query}%")->get();
        
        return response()->json($rooms);
    }

    // SEARCH FUNCTIONALITY HERE! FOR STUDENTS SIDE!
    public function searchStudent(Request $request) {

        $query = $request->get('query');
        // $rooms = Rooms::orderBy('created_at', 'desc')->where('class_name', 'LIKE', "%{$query}%")->get();
        $roomStudent = StudentRoom::orderBy('created_at', 'desc')->where('class_name', 'LIKE', "%{$query}%")->get();
        
        return response()->json($roomStudent);
    }

    // Cancel Button
    public function choices() {
        $userID = Auth::id();
        $user = User::find($userID);
    
        $role = $user->role;
    
        // Role-based redirection
        $redirectRoutes = [
            'Teacher' => 'teacher-home',
            'Student' => 'student-home',
        ];
    
        return isset($redirectRoutes[$role]) ? redirect()->route($redirectRoutes[$role]) : redirect()->intended('/home');
    }

    // CLASS RECORD
    public function showSample(Request $request, $id) {
        
        $userId = Auth::id();
        $tableID = $request->input('key');

        $decyptedRoomID = HelperFunctions::base64_url_decode($id);

        $room = Rooms::where('id', $decyptedRoomID)->where('teacher_id', $userId)->first();
        $encodedID = HelperFunctions::base64_url_encode($room->id);
        // $encryptedRoomID = Crypt::encrypt($room->id);
        $roomID = TableSkills::where('room_id', $decyptedRoomID)->get();
        $dataRatings = TableRatings::where('room_id', $decyptedRoomID)->get();

        $studentNames = StudentNames::where('room_id', $decyptedRoomID)->orderBy('name_3')->get();
        $studentName = StudentNames::where('room_id', $decyptedRoomID)->first();
        $teacherName = Rooms::where('id', $decyptedRoomID)->first();
        $formula = TableFormula::where('room_id', $decyptedRoomID)->where('table_id', $tableID)->get();

        // $formula = TableFormula::where('room_id', $id)->get();

        $count = StudentNames::where('room_id', $decyptedRoomID)->count();

        $totalRowCol = TableTotalRowCol::where('table_id', $tableID)->where('teacher_id', $userId)->where('room_id', $decyptedRoomID)->first();
        $doneCheck = TableDoneCheck::where('table_id', $tableID)->where('teacher_id', $userId)->where('room_id', $decyptedRoomID)->get();
        $checkedCol = [];
        foreach($doneCheck as $checked) {
            $checkedCol[] = $checked->column;
        }

        $selectedRow = TableSelectedRowCol::where('table_id', $tableID)->where('teacher_id', $userId)->where('room_id', $decyptedRoomID)->first();

        $presentColRow = DB::table('table_selected_row_col')
        ->join('student_names', 'table_selected_row_col.room_id', '=', 'student_names.room_id')
        ->where('table_selected_row_col.teacher_id', '=', $userId)
        ->where('student_names.room_id', '=', $decyptedRoomID)
        ->select('table_selected_row_col.*', 'student_names.name_3') // Select columns from both tables
        ->first();

        $columnLabels = ['#1', 'Name', 'M.C', 'M.P', 'M.A', 'MidGr.','Mid.N.Eqv.', 'F.C', 'F.P', 'F.A','T.F.Gr.','F.N.Eqv.', 'Mid', 'Fin', 'FR.Eqv', 'FR.N.Eqv', 'Credits', 'Remarks', '#2'];

        $getUnit = RatingSheet::where('table_id', 'unit')->where('room_id', $decyptedRoomID)->first();
        $getSem = RatingSheet::where('table_id', 'semester')->where('room_id', $decyptedRoomID)->first();

        $getMidGr = RatingSheet::where('table_id', 'MidGr.')->where('room_id', $decyptedRoomID)->first();
        $getFinGr = RatingSheet::where('table_id', 'T.F.Gr.')->where('room_id', $decyptedRoomID)->first();

        return view('records.sample', ['data' => $roomID, 'dataRatings' => $dataRatings, 'encodedID' => $encodedID, 'room' => $room, 'names' => $studentNames, 'name' => $studentName, 'teacher' => $teacherName, 'counts' => $count, 'selectedRow' => $selectedRow, 'presentColRow' => $presentColRow, 'usedFormula' => $formula, 'totalRowCol' => $totalRowCol, 'doneCheck' => $checkedCol, 'columnLabels' => $columnLabels, 'unit' => $getUnit, 'sem' => $getSem, 'getMidGr' => $getMidGr, 'getFinGr' => $getFinGr]);
    }

    public function getFormulaForRating(Request $request) {
        $userId = Auth::id();
        $userName = Auth::user()->username;
        $formula = $request->input('column'); // Example: "=D"
        // $column = ltrim($formula, '='); // Remove '=' to get column name
        // $teacherId = $request->input('teacher_id');
        $roomId = $request->input('room_id');
        $currentCol = $request->input('currentCol');

        // Retrieve matching skills
        $skills = TableSkills::where('column', $formula)
        ->where('room_id', $roomId)
        ->where('teacher_id', $userId)
        ->where('student_name', '!=', $userName) // Exclude the current user's name
        ->get();

        if ($skills->isEmpty()) {
            return response()->json(['error' => 'No matching records found'], 404);
        }

        // Prepare data for insertion
        $ratings = $skills->map(function ($skill) use ($currentCol) {
            return [
                'teacher_id'   => $skill->teacher_id,
                'room_id'      => $skill->room_id,
                'student_name' => $skill->student_name,
                'column'       => $currentCol,  
                'row'          => $skill->row,
                'content'      => $skill->content,
                'merged'       => $skill->merged,
                'rowspan'      => $skill->rowspan,
                'colspan'      => $skill->colspan,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        })->toArray();

        TableRatings::insert($ratings);

        return response()->json(['message' => 'Formula applied successfully', 'data' => $ratings]);
    }

    public function getUnits(Request $request) {

        $userId = Auth::id();
        $unitValue = $request->input('unitValue');
        $roomId = $request->input('room_id');

        $existingUnit = RatingSheet::where('table_id', 'unit')->where('room_id', $roomId)->where('teacher_id', $userId)->first();

        if ($existingUnit) {
            $existingUnit->update(['column' => $unitValue]);
        } else {
            $insertUnit = [
                'table_id' => 'unit',
                'teacher_id' => $userId,
                'room_id' => $roomId,
                'column' => $unitValue
            ];
            // If not exists, insert a new row
            RatingSheet::create($insertUnit);
        }

        return response()->json(['message' => 'Formula applied successfully', 'data' => $unitValue]);

    }

    public function getSem(Request $request) {
        $userId = Auth::id();
        $unitValue = $request->input('semValue');
        $tableId = $request->input('table_id'); // Get dynamic table_id
        $roomId = $request->input('room_id');
    
        if (!$unitValue || !$tableId || !$roomId) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }
    
        // Check if a record with the same table_id and room_id exists
        $existingUnit = RatingSheet::where('table_id', $tableId)
            ->where('room_id', $roomId)
            ->where('teacher_id', $userId)
            ->first();
    
        if ($existingUnit) {
            // If exists, update the column
            $existingUnit->update(['unit_column' => $unitValue]); 
        } else {
            // If not exists, insert a new row
            RatingSheet::create([
                'table_id' => $tableId, // Store dynamic table_id
                'teacher_id' => $userId,
                'room_id' => $roomId,
                'column' => $unitValue
            ]);
        }
    
        return response()->json(['message' => 'Formula applied successfully', 'data' => $unitValue]);
    }
    

    public function getNumberGrade(Request $request) {

        $grades = $request->input('grades');

        foreach ($grades as $grade) {
            TableRatings::updateOrInsert(
                [
                    'teacher_id' => $grade['teacher_id'],
                    'room_id' => $grade['room_id'],
                    'student_name' => trim($grade['student_name']), // Trim to avoid whitespace mismatches
                    'column' => trim($grade['column']),
                    'row' => $grade['row'],
                ],
                [
                    'content' => $grade['content'], // Only update content
                ]
            );
        }
        
        

        return response()->json(['message' => 'Formula applied successfully']);
    
    }

    public function getRoomID($id) {
        
        $userId = Auth::id();

        $decyptedRoomID = HelperFunctions::base64_url_decode($id);

        $room = Rooms::where('id', $decyptedRoomID)->where('teacher_id', $userId)->first();
        $encodedID = HelperFunctions::base64_url_encode($room->id);

        // Get the names of students to show in Pastenames
        $getNames = StudentNames::where('room_id', $decyptedRoomID)->orderBy('name_3')->get();
        $getId = StudentNames::where('room_id', $decyptedRoomID)->first();
        $getRoomAndName = StudentRoom::where('room_id', $decyptedRoomID)->get();

        

        // foreach($getRoomAndName as $roomAndName) {
        //     $roomName = $roomAndName->student_name;
        // }

        return view('records.pasteNames', [ 'room' => $room, 'encodedID' => $encodedID, 'getNames' => $getNames, 'roomId' => $getId, 'getRoomAndName' => $getRoomAndName]);
    }

    public function pasteStudentNames(Request $request, $id) {
        $request->validate([
            'pasted_text' => 'required|string',
        ]);
    
        // Process the pasted text
        $structuredNames = $this->processPastedText($request->input('pasted_text'));
    
        // Save the names to the database

        foreach ($structuredNames as $row) {
            $data = [
                'room_id' => $id,
                'name_1' => $row[0] ?? null,
                'name_2' => $row[1] ?? null,
                'name_3' => $row[2] ?? null,
            ];
    
            StudentNames::create($data);
        }
    
        // return redirect()->route('paste.student.names', ['id' => $id]);
        return redirect()->back();
    }

    // ADD STUDENT in PASTING NAME
    public function addStudent(Request $request, $id) {

        $request->validate([
            'pasted_text' => 'required|string',
        ]);
    
        // Process the pasted text
        $structuredNames = $this->processPastedText($request->input('pasted_text'));
    
        // Save the names to the database
        foreach ($structuredNames as $row) {
            $data = [
                'room_id' => $id,
                'name_1' => $row[0] ?? null,
                'name_2' => $row[1] ?? null,
                'name_3' => $row[2] ?? null,
            ];
    
            StudentNames::create($data);
        }
    
        return redirect()->back();    
    }
    
    // BUTANG NALANG FUNCTION NGA KUNG MAG PASTE NAMES THEN BUTANG DOT KADA NAMES
    private function processPastedText($pastedText) {
        $nameRows = [];

        $nameRows = [];
    
        // Normalize spaces and trim the pasted text
        $normalizedText = preg_replace('/\s+/', ' ', trim($pastedText));
    
        $normalizedText = str_replace('.', '', $normalizedText);
        
        // List of common suffixes
        $suffixes = ['Jr', 'JR', 'Sr', 'SR', 'II', 'III', 'IV', 'll', 'lll', 'lV'];
        $suffixesWithPeriod = ['Jr', 'JR', 'Sr', 'SR'];
    
        $normalizedText = preg_replace_callback(
            '/(?<![\d-])\b([A-Z])\b(?![\d-])(?!\s*\b(?:' . implode('|', $suffixes) . ')\b|\s*-\s*\b)/',
            function ($matches) {
                return $matches[1] . '.';
            },
            $normalizedText
        );
    
        // Check if the suffix is before a single letter and adjust splitting logic accordingly
        if (preg_match('/\b(' . implode('|', $suffixes) . ')\s+[A-Z]\b/', $normalizedText)) {
            $lines = preg_split('/(?<=\.)\s*/', $normalizedText);
        } else {
            $lines = preg_split('/(?<=\.)\s*|(?<=\bII\b|\bIII\b|\bIV\b|\bll\b|\blll\b|\blV\b|\bSR\b|\bSr\b|\bJR\b|\bJr)\s+(?=[A-Z]|\d)/', $normalizedText);
        }
    
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
    
            $line = preg_replace_callback('/\b([A-Z])\s+(' . implode('|', $suffixes) . ')\b/', function ($matches) {
                return $matches[1] . '. ' . $matches[2];
            }, $line);
    
            $line = preg_replace_callback('/\b(' . implode('|', $suffixesWithPeriod) . ')(?!\.)\b/', function ($matches) {
                return $matches[1] . '.'; // Add a period after the suffix
            }, $line);
    
            // Match and process lines
            if (preg_match('/^(\d+)\s+(\d{4}-\d{4}-[A-Z])\s+(.+)$/', $line, $matches)) {
                // Matches ID, code, and name
                $studentId = trim($matches[1]);
                $studentCode = trim($matches[2]);
                $studentName = trim($matches[3]);
            } elseif (preg_match('/^(\d+)\s+(.+)$/', $line, $matches)) {
                // Matches ID and name without code
                $studentId = trim($matches[1]);
                $studentCode = null;
                $studentName = trim($matches[2]);
            } elseif (preg_match('/^(\d{4}-\d{4}-[A-Z])\s+(.+)$/', $line, $matches)) {
                // Matches code and name without ID
                $studentId = null;
                $studentCode = trim($matches[1]);
                $studentName = trim($matches[2]);
            } else {
                // Handle the case where the line consists of only names separated by a single letter
                if (preg_match('/^([A-Za-z]+)\s*$/', $line)) {
                $studentId = null;
                $studentCode = null;
                $studentName = trim($matches[1]);
                }
    
                // Everything else as name only
                $studentId = null;
                $studentCode = null;
                $studentName = $line;
            }
            $nameRows[] = [$studentId, $studentCode, $studentName];
        }
    
        return $nameRows;
    }

    
    
    
    
public function structureNames(Request $request)
{
    // Get the pasted text from the request
    $pastedText = $request->input('pastedText');

    // The function to structure the pasted names
    $structuredNames = $this->structuredPastedText($pastedText);

    // Return the structured names as a response
    return response()->json(['structuredNames' => $structuredNames]);
}


private function structuredPastedText($pastedText) {
    $nameRows = [];
    
    // Normalize spaces and trim the pasted text
    $normalizedText = preg_replace('/\s+/', ' ', trim($pastedText));

    $normalizedText = str_replace('.', '', $normalizedText);
    
    // List of common suffixes
    $suffixes = ['Jr', 'JR', 'Sr', 'SR', 'II', 'III', 'IV', 'll', 'lll', 'lV'];
    $suffixesWithPeriod = ['Jr', 'JR', 'Sr', 'SR'];
    
// ARI GA WORK ONLY KUNG MAY ID KAG NAME PERO WALA KUNG NAME LANG
    // Add periods to single letters that do not have them, but exclude ones followed by suffixes
    // $normalizedText = preg_replace_callback('/(?<![\d-])\b([A-Z])\b(?![\d-])/', function ($matches) use ($suffixes, $normalizedText) {
    //     $nextWords = substr($normalizedText, strpos($normalizedText, $matches[0]) + strlen($matches[0]));
    //     $nextWordsArray = explode(' ', trim($nextWords));
        
    //     // Check if the next word is a suffix
    //     if (isset($nextWordsArray[0]) && in_array(rtrim($nextWordsArray[0], '.'), $suffixes)) {
    //         return $matches[1]; // Do not add a period if suffix is present after the letter
    //     }
        
    //     return $matches[1] . '.';
    // }, $normalizedText);

// ARI GA WORK KUNG ONLY NAMES LANG PERDO INDI KUNG PATI AND ID
    $normalizedText = preg_replace_callback(
        '/(?<![\d-])\b([A-Z])\b(?![\d-])(?!\s*\b(?:' . implode('|', $suffixes) . ')\b|\s*-\s*\b)/',
        function ($matches) {
            return $matches[1] . '.';
        },
        $normalizedText
    );

    // Check if the suffix is before a single letter and adjust splitting logic accordingly
    if (preg_match('/\b(' . implode('|', $suffixes) . ')\s+[A-Z]\b/', $normalizedText)) {
        $lines = preg_split('/(?<=\.)\s*/', $normalizedText);
    } else {
        $lines = preg_split('/(?<=\.)\s*|(?<=\bII\b|\bIII\b|\bIV\b|\bll\b|\blll\b|\blV\b|\bSR\b|\bSr\b|\bJR\b|\bJr)\s+(?=[A-Z]|\d)/', $normalizedText);
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }

        // Adding . to single letters missed due to suffix check
        $line = preg_replace_callback('/\b([A-Z])\s+(' . implode('|', $suffixes) . ')\b/', function ($matches) {
            return $matches[1] . '. ' . $matches[2];
        }, $line);

        $line = preg_replace_callback('/\b(' . implode('|', $suffixesWithPeriod) . ')(?!\.)\b/', function ($matches) {
            return $matches[1] . '.'; // Add a period after the suffix
        }, $line);
        
        if (preg_match('/^(\d+)\s+(\d{4}-\d{4}-[A-Z])\s+(.+)$/', $line, $matches)) {
            $studentId = trim($matches[1]);
            $studentCode = trim($matches[2]);
            $studentName = trim($matches[3]);
        } elseif (preg_match('/^(\d+)\s+(.+)$/', $line, $matches)) {
            $studentId = trim($matches[1]);
            $studentCode = null;
            $studentName = trim($matches[2]);
        } elseif (preg_match('/^(\d{4}-\d{4}-[A-Z])\s+(.+)$/', $line, $matches)) {
            $studentId = null;
            $studentCode = trim($matches[1]);
            $studentName = trim($matches[2]);
        } else {
            $studentId = null;
            $studentCode = null;
            $studentName = $line;
        }
    
        $nameRows[] = implode(' ', array_filter([$studentId, $studentCode, $studentName]));
    }
    
    return implode("\n", $nameRows);
}


    
    
    



    // ATTENDANCE
    public function showAttendance(Request $request, $id) {
        
        $userId = Auth::id();
        $tableID = $request->input('key');

        $decyptedRoomID = HelperFunctions::base64_url_decode($id);

        $room = Rooms::where('id', $decyptedRoomID)->where('teacher_id', $userId)->first();
        $roomID = TableAttendance::where('room_id', $decyptedRoomID)->get();

        $encodedID = HelperFunctions::base64_url_encode($room->id);

        $studentNames = StudentNames::where('room_id', $decyptedRoomID)->orderBy('name_3')->get();
        $studentName = StudentNames::where('room_id', $decyptedRoomID)->first();
        $teacherName = Rooms::where('id', $decyptedRoomID)->first();

        $formula = TableFormula::where('room_id', $decyptedRoomID)->where('table_id', $tableID)->get();

        $count = StudentNames::where('room_id', $decyptedRoomID)->count();

        $totalRowCol = TableTotalRowCol::where('table_id', $tableID)->where('teacher_id', $userId)->where('room_id', $decyptedRoomID)->first();
        $doneCheck = TableDoneCheck::where('table_id', $tableID)->where('teacher_id', $userId)->where('room_id', $decyptedRoomID)->get();
        $checkedCol = [];
        foreach($doneCheck as $checked) {
            $checkedCol[] = $checked->column;
        }
        $selectedRow = TableSelectedRowCol::where('table_id', 'attendance-table')->where('teacher_id', $userId)->where('room_id', $decyptedRoomID)->first();

        $presentColRow = DB::table('table_selected_row_col')
        ->join('student_names', 'table_selected_row_col.room_id', '=', 'student_names.room_id')
        ->where('table_selected_row_col.teacher_id', '=', $userId)
        ->where('student_names.room_id', '=', $decyptedRoomID)
        ->select('table_selected_row_col.*', 'student_names.name_3') // Select columns from both tables
        ->get();

        return view('records.attendance', ['data' => $roomID, 'room' => $room,  'encodedID' => $encodedID, 'names' => $studentNames, 'name' => $studentName, 'teacher' => $teacherName, 'counts' => $count, 'selectedRow' => $selectedRow, 'presentColRow' => $presentColRow, 'usedFormula' => $formula, 'totalRowCol' => $totalRowCol, 'doneCheck' => $checkedCol]);
    }

    // RATINGS
    public function showRatings(Request $request, $id) {

        $userId = Auth::id();
        $tableID = $request->input('key');

        $decyptedRoomID = HelperFunctions::base64_url_decode($id);

        $room = Rooms::where('id', $decyptedRoomID)->where('teacher_id', $userId)->first();
        $encodedID = HelperFunctions::base64_url_encode($room->id);
        $roomID = TableRatings::where('room_id', $decyptedRoomID)->get();

        $studentNames = StudentNames::where('room_id', $decyptedRoomID)->orderBy('name_3')->get();
        $studentName = StudentNames::where('room_id', $decyptedRoomID)->first();
        $teacherName = Rooms::where('id', $decyptedRoomID)->first();
        $formula = TableFormula::where('room_id', $decyptedRoomID)->where('table_id', $tableID)->get();

        // $formula = TableFormula::where('room_id', $id)->get();

        $count = StudentNames::where('room_id', $decyptedRoomID)->count();

        $selectedRow = TableSelectedRowCol::where('table_id', 'main-table')->where('teacher_id', $userId)->where('room_id', $decyptedRoomID)->first();

        $presentColRow = DB::table('table_selected_row_col')
        ->join('student_names', 'table_selected_row_col.room_id', '=', 'student_names.room_id')
        ->where('table_selected_row_col.teacher_id', '=', $userId)
        ->where('student_names.room_id', '=', $decyptedRoomID)
        ->select('table_selected_row_col.*', 'student_names.name_3') // Select columns from both tables
        ->first();

        $columnLabels = ['#1', 'Name', 'M.C', 'M.P', 'M.A', 'MidGr.','Mid.N.Eqv.', 'F.C', 'F.P', 'F.A','T.F.Gr.','F.N.Eqv.', 'Mid%', 'Fin%', 'FR%Eqv.', 'FR.N.Eqv.', 'Credits', 'Remarks', '#2'];
        // $sequences = $this->generateSequences(104);

        return view('records.ratings', ['data' => $roomID, 'encodedID' => $encodedID, 'room' => $room, 'names' => $studentNames, 'name' => $studentName, 'teacher' => $teacherName, 'counts' => $count, 'selectedRow' => $selectedRow, 'presentColRow' => $presentColRow, 'usedFormula' => $formula, 'columnLabels' => $columnLabels]);

    }


    //MERGING CELLS HERE
    public function saveMergedCell(Request $request)
    {
        try {
            $userID = Auth::id();
    
            $tableId = $request->tableID;
    
            $tableModels = [
                'main-table' => TableSkills::class,
                'attendance-table' => TableAttendance::class
            ];
    
            if (!array_key_exists($tableId, $tableModels)) {
                return response()->json(['message' => 'Invalid table ID.'], 400);
            }
    
            $model = $tableModels[$tableId];
    
            // Validate the request input
            $request->validate([
                'content' => ['nullable'],
                'hiddenCells' => ['array']
            ]);
    
            // Find the main cell (if it exists) by row and column
            $mainCell = $model::where('row', $request->row)
                ->where('column', $request->column)
                ->where('teacher_id', $userID)
                ->where('room_id', $request->room_id)
                // ->where('student_room_id', $request->student_id)
                ->first();
    
            // If the main cell exists, update it
            if ($mainCell) {
                $mainCell->update([
                    'content' => $request->content,
                    'rowspan' => $request->rowspan,
                    'colspan' => $request->colspan
                ]);
            } else {
                // If the main cell doesn't exist, create it
                $mainCell = $model::create([
                    'teacher_id' => $userID,
                    'room_id' => $request->room_id,
                    // 'student_room_id' => $request->student_id,
                    'student_name' => $request->student_name,
                    'row' => $request->row,
                    'column' => $request->column,
                    'content' => $request->content,
                    'merged' => true,
                    'rowspan' => $request->rowspan,
                    'colspan' => $request->colspan
                ]);
            }
    
            // Save hidden cells
            foreach ($request->hiddenCells as $hiddenCell) {
                TableMerge::create([
                    'table_id' => $request->tableID,
                    'teacher_id' => $userID,
                    'room_id' => $request->room_id,
                    // 'student_room_id' => $request->student_id,
                    'student_name' => $request->student_name,
                    'row' => $hiddenCell['row'],
                    'column' => $hiddenCell['column'],
                    'content' => $hiddenCell['content'],
                    'merged' => $hiddenCell['merged'],
                    'rowspan' => $hiddenCell['rowspan'],
                    'colspan' => $hiddenCell['colspan'],
                ]);
            }
    
            return response()->json(['message' => 'Cell merge saved successfully.', 'id' => $mainCell->id], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving cell merge: ' . $e->getMessage()], 500);
        }
    }
    

    // UPDATING MERGED CELLS HERE
    public function updateMergedCell(Request $request, $id)
    {
        $tableId = $request->tableID;
        
        $tableModels = [
            'main-table' => TableSkills::class,
            'attendance-table' => TableAttendance::class
        ];
        
        if (!array_key_exists($tableId, $tableModels)) {
            return response()->json(['message' => 'Invalid table ID.'], 400);
        }

        $model = $tableModels[$tableId];

        try {
            $cell = $model::findOrFail($id);
            
            $cell->update([
                'content' => $request->input('content'),
                'merged' => $request->input('merged'),
                'colspan' => $request->input('colspan'),
                'rowspan' => $request->input('rowspan')
            ]);

            return response()->json(['message' => 'Cell updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating cell: ' . $e->getMessage()], 500);
        }
    }

    public function updateContentCell(Request $request, $id)
    {
        $tableId = $request->input('tableId');
        $tableModels = [
            'main-table' => TableSkills::class,
            'attendance-table' => TableAttendance::class,
            'rating-table' => TableRatings::class
        ];
    
        if (!array_key_exists($tableId, $tableModels)) {
            return response()->json(['message' => 'Invalid table ID.'], 400);
        }
    
        $model = $tableModels[$tableId];
    
        try {
            $cell = $model::findOrFail($id);  // If ID is missing or invalid, this will fail
    
            $cell->update([
                'content' => $request->input('content'),
                'merged' => $request->input('merged'),
                'colspan' => $request->input('colspan'),
                'rowspan' => $request->input('rowspan')
            ]);
    
            return response()->json(['message' => 'Cell updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating cell: ' . $e->getMessage()], 500);
        }
    }

    // CALCULATING AND SAVING THE DATA
    public function saveCalculatedContentCell(Request $request)
    {
        $teacherID = Auth::id();
        $tableId = $request->input('tableId');
        $tableModels = [
            'main-table' => TableSkills::class,
            'attendance-table' => TableAttendance::class,
            'rating-table' => TableRatings::class
        ];
    
        if (!array_key_exists($tableId, $tableModels)) {
            return response()->json(['message' => 'Invalid table ID.'], 400);
        }
    
        $model = $tableModels[$tableId];
    
        try {

            $model::create([
                'teacher_id' => $teacherID,
                'room_id' => $request->room_id,
                'student_name' => $request->student_name,
                'row' => $request->row,
                'column' => $request->column,
                'content' => $request->content,
                'rowspan' => $request->rowspan,
                'colspan' => $request->colspan,
            ]);

            // $cell->update([
            //     'content' => $request->input('content'),
            //     'merged' => $request->input('merged'),
            //     'colspan' => $request->input('colspan'),
            //     'rowspan' => $request->input('rowspan')
            // ]);

    
            return response()->json(['message' => 'Calculated cell saved successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving calculated cell: ' . $e->getMessage()], 500);
        }
    }


    public function bulkUpdateCells(Request $request)
    {
        try {
            $tableId = $request->input('tableId');
            $cells = $request->input('cells');
            $teacherId = Auth::id();
    
            $tableModels = [
                'main-table' => TableSkills::class,
                'attendance-table' => TableAttendance::class,
                'rating-table' => TableRatings::class
            ];
    
            if (!array_key_exists($tableId, $tableModels)) {
                return response()->json(['message' => 'Invalid table ID.'], 400);
            }
    
            $model = $tableModels[$tableId];
    
            $updatedIds = [];
            $updateData = [];
            $insertData = [];
    
            // Get all existing cell IDs from the request
            $cellIds = array_filter(array_column($cells, 'id'));
    
            // Fetch existing IDs from the database
            $existingIds = $model::whereIn('id', $cellIds)->pluck('id')->toArray();
    
            foreach ($cells as $cell) {
                if (isset($cell['id']) && in_array($cell['id'], $existingIds)) {
                    // Update existing cell
                    $updateData[] = [
                        'id' => $cell['id'],
                        'teacher_id' => $teacherId,
                        'content' => $cell['content'],
                        'merged' => $cell['merged'] ?? 0,
                        'colspan' => $cell['colspan'] ?? 1,
                        'rowspan' => $cell['rowspan'] ?? 1,
                        'updated_at' => now()
                    ];
                    $updatedIds[] = $cell['id'];
                } else {
                    // Insert new cell if ID is missing or not found
                    $insertData[] = [
                        'teacher_id' => $teacherId,
                        'table_id' => $tableId,
                        'content' => $cell['content'],
                        'merged' => $cell['merged'] ?? 0,
                        'colspan' => $cell['colspan'] ?? 1,
                        'rowspan' => $cell['rowspan'] ?? 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
    
            // Bulk insert new records
            if (!empty($insertData)) {
                $model::insert($insertData);
            }
    
            // Bulk update existing records
            if (!empty($updateData)) {
                foreach ($updateData as $data) {
                    $model::where('id', $data['id'])->update([
                        'content' => $data['content'],
                        'merged' => $data['merged'],
                        'colspan' => $data['colspan'],
                        'rowspan' => $data['rowspan'],
                        'updated_at' => $data['updated_at']
                    ]);
                }
            }
    
            return response()->json([
                'message' => 'Cells updated successfully.',
                'ids' => $updatedIds
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating cells.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    

    // public function updateAttendance(Request $request, $id)
    // {
    //     try {
    //         $cell = TableSkills::findOrFail($id);

    //         $cell->update(['content' => $request->content, 'merged' => $request->merged, 'colspan' => $request->colspan, 'rowspan' => $request->rowspan]);

    //         return response()->json(['message' => 'Cell Updated successfully.'], 200);


    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Error Updating cell: ' . $e->getMessage()], 500);
    //     }
    // }

    // UNMERGED CELL HERE
    // public function unMergedCell(Request $request, $id)
    // {
    //     try {
    //         $cell = TableSkills::findOrFail($id);

    //         $cell->update(['merged' => $request->merged, 'colspan' => $request->colspan, 'rowspan' => $request->rowspan]);

    //         return response()->json(['message' => 'Cell Unmerged successfully.'], 200);


    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Error Updating cell: ' . $e->getMessage()], 500);
    //     }
    // }
    public function unMergedCell(Request $request)
    {
        try {
            $userID = Auth::id();
    
            $tableId = $request->tableID;
    
            $tableModels = [
                'main-table' => TableSkills::class,
                'attendance-table' => TableAttendance::class
            ];
    
            if (!array_key_exists($tableId, $tableModels)) {
                return response()->json(['message' => 'Invalid table ID.'], 400);
            }
    
            $model = $tableModels[$tableId];
    
            // Validate the request input
            $request->validate([
                'cell_id' => ['required', 'integer'],
                'row' => ['required', 'integer'],
                'column' => ['required', 'string'],
                'rowspan' => ['required', 'integer'],
                'colspan' => ['required', 'integer']
            ]);
    
            // Find the main cell using its ID
            $mainCell = $model::where('id', $request->cell_id)
                ->where('teacher_id', $userID)
                ->first();
    
            if (!$mainCell) {
                return response()->json(['message' => 'Main cell not found.'], 404);
            }
    
            // Update the main cell to reset its rowspan and colspan
            $mainCell->update([
                'rowspan' => 1,
                'colspan' => 1,
                'merged' => false
            ]);

            $cellAttr = [
                    'teacher_id' => $userID,
                    'room_id' => $request->room_id,
                    'data_room_student' => $mainCell->student_name,
                    'data_room_studentid' => $mainCell->student_room_id,
                    'table_id' => $tableId
                ];
    
            // Initialize an array to collect updated cell data
            $updatedCells = [];
    
            // Handle the hidden cells that were part of the merged area
            $startRow = $request->row;
            $startCol = $this->columnNameToIndex($request->column);
            $endRow = $startRow + $request->rowspan - 1;
            $endCol = $startCol + $request->colspan - 1;

            $columnStart = $this->columnIndexToName($startCol);
            $columnEnd = $this->columnIndexToName($endCol);
    
            // Find hidden cells that need to be restored
            $hiddenCells = TableMerge::where('table_id', $tableId)
                ->where('teacher_id', $userID)
                ->where('room_id', $request->room_id)
                ->whereBetween('row', [$startRow, $endRow])
                ->whereBetween('column', [$columnStart, $columnEnd])
                ->get();
    
            foreach ($hiddenCells as $hiddenCell) {
                // Reset each hidden cell to be visible again
                $hiddenCell->update([
                    'merged' => false,
                    'rowspan' => 1,
                    'colspan' => 1
                ]);
    
                // Prepare data to update the frontend
                $updatedCells[] = [
                    'row' => $hiddenCell->row,
                    'column' => $hiddenCell->column,
                    'rowspan' => 1,
                    'colspan' => 1,
                    'content' => $hiddenCell->content
                ];
            }
    
            // Optionally, delete the hidden cell records if they're no longer needed
            // TableMerge::where('table_id', $tableId)
            //     ->where('teacher_id', $userID)
            //     ->where('room_id', $request->room_id)
            //     ->whereBetween('row', [$startRow, $endRow])
            //     ->whereBetween('column', [$columnStart, $columnEnd])
            //     ->delete();

            TableMerge::where('table_id', $tableId)
            ->where('teacher_id', $userID)
            ->where('room_id', $request->room_id)
            ->whereBetween('row', [$startRow, $endRow])
            ->whereBetween('column', [$columnStart, $columnEnd])
            ->delete();

    
            // Return only the updated hidden cells to the frontend
            return response()->json(['message' => 'Cells unmerged successfully.', 'updatedCells' => $updatedCells, 'mainCell' => $mainCell, 'cellAttr' => $cellAttr], 200);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error during cell unmerge: ' . $e->getMessage()], 500);
        }
    }
    
    private function columnNameToIndex($columnName)
    {
        $columnName = strtoupper($columnName);
        $length = strlen($columnName);
        $index = 0;
    
        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($columnName[$i]) - ord('A')) + 1;
        }
    
        return $index;
    }
    
    
    private function columnIndexToName($index)
    {
        $columnName = '';
    
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $columnName = chr($mod + 65) . $columnName;
            $index = (int)(($index - $mod) / 26);
        }
    
        return $columnName;
    }

    public function getCellStates(Request $request) {
        $userID = Auth::id();
        $tableID = $request->tableID;
        $roomID = $request->roomID;
    
        $cells = TableMerge::where('table_id', $tableID)
            ->where('teacher_id', $userID)
            ->where('room_id', $roomID)
            ->get(['row', 'column', 'rowspan', 'colspan', 'content', 'merged']);
    
        return response()->json(['cells' => $cells], 200);
    }
    
    
    
    // BASI MAGAMIT MO PANIIIIIIII
    // public function unMergedCell(Request $request, $id)
    // {
    //     try {
    //         $userID = Auth::id();
    
    //         // Find the main merged cell by ID
    //         $cell = TableSkills::findOrFail($id);
    
    //         // Update the main cell to remove merge properties
    //         $cell->update([
    //             'merged' => $request->merged,
    //             'colspan' => $request->colspan,
    //             'rowspan' => $request->rowspan,
    //         ]);
    
    //         // Restore the hidden cells that were merged into the main cell
    //         if (isset($request->hiddenCells) && is_array($request->hiddenCells)) {
    //             foreach ($request->hiddenCells as $hiddenCellData) {
    //                 // Find each hidden cell in the database
    //                 $hiddenCell = TableSkills::where('row', $hiddenCellData['row'])
    //                     ->where('column', $hiddenCellData['column'])
    //                     ->where('teacher_id', $userID)
    //                     ->where('room_id', $cell->room_id)
    //                     ->where('student_room_id', $cell->student_room_id)
    //                     ->first();
    
    //                 // If hidden cell exists, restore its properties
    //                 if ($hiddenCell) {
    //                     $hiddenCell->update([
    //                         'merged' => $hiddenCellData['merged'],
    //                         'colspan' => $hiddenCellData['colspan'],
    //                         'rowspan' => $hiddenCellData['rowspan'],
    //                         'content' => $hiddenCellData['content']
    //                     ]);
    //                 }
    //             }
    //         }
    
    //         return response()->json(['message' => 'Cell Unmerged successfully.'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Error Updating cell: ' . $e->getMessage()], 500);
    //     }
    // }

    


    public function loadTableState(Request $request) {

        try {
            $teacherId = $request->query('teacher_id');
            $roomId = $request->query('room_id');

            $tableState = TableSkills::where('teacher_id', $teacherId)
                                    ->where('room_id', $roomId)
                                    ->get();

            return response()->json($tableState, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error loading table state: ' . $e->getMessage()], 500);
        }

    }

    public function loadPage() {

        try {
            $data = TableSkills::all(); 

            $html = view('records.sample', ['data' => $data])->render();
    
            return $html;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error loading Page: ' . $e->getMessage()], 500);
        }
        
    }

    public function fetchStudents($id) {

        try {
            $students = StudentRoom::where('room_id', $id)->get();
    
            return response()->json(['students' => $students]);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error loading Page: ' . $e->getMessage()], 500);
        }

    }






// TO MAKE DATA_STUDENT PERSISTENT
public function updateStudents(Request $request)
{

    try {
        $updates = $request->input('updates');
        $roomId = $request->input('room_id');
    
        foreach ($updates as $update) {
            $row = TableSkills::where('id', $update['row'])->where('room_id', $roomId)->first();
            if ($row) {
                foreach ($row->cells as $cell) {
                    $cell->student_name = $update['student_name'];
                    $cell->save();
                }
            }
        }

    } catch(\Exception $e) {
        return response()->json(['message' => 'Error Updating: ' . $e->getMessage()], 500);
    }

    // return response()->json(['status' => 'success']);
}

// UPDATING THE TOTAL
public function updateTotal(Request $request)
    {
        // room_id: $(selectedCells[0]).attr('data-room-id'),
        // student_id: $(selectedCells[0]).attr('data-room-studentid'),
        // student_name: $(selectedCells[0]).attr('data-room-student'),
        // room_studentID: $(selectedCells[0]).attr('data-room-studentID'),
        // column: $(selectedCells[selectedCells.length - 1]).attr('data-column'),
        // row: row.index(),
        // content: rowResult,
        // rowspan: $(selectedCells[0]).attr('rowspan'),
        // colspan: $(selectedCells[0]).attr('colspan')

        // Find the existing record based on room_id, student_id, column, and row
        $skill = TableSkills::where('room_id', $request->room_id)
                      ->where('student_id', $request->student_id)
                      ->where('column', $request->column)
                      ->where('row', $request->row)
                      ->first();

        if ($skill) {
            // Update the existing record
            $skill->content = $request->content;
            $skill->save();

            return response()->json(['message' => 'Skill updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Skill not found'], 404);
        }
    }

    // Update Student Names
    public function updateStudentCell(Request $request, $id)
    {
        $tdID = $request->input('tdID');
        
        $columnName = [
            'studentIdNo' => 'name_2',
            'studentName' => 'name_3'
        ];
        
        if (!array_key_exists($tdID, $columnName)) {
            return response()->json(['message' => 'Invalid table ID.'], 400);
        }

        $colName = $columnName[$tdID];

        try {
            $cell = StudentNames::findOrFail($id);
            
            $cell->update([
                $colName => $request->input('content')
            ]);

            return response()->json(['message' => 'Cell updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating cell: ' . $e->getMessage()], 500);
        }
    }

}

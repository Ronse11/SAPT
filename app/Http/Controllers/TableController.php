<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\StudentNames;
use App\Models\StudentRoom;
use App\Models\TableAttendance;
use App\Models\TableAttitude;
use App\Models\TableData;
use App\Models\TableFormula;
use App\Models\TableKnowledge;
use App\Models\TableRatings;
use App\Models\TableSkills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TableController extends Controller
{

    // KNOWLEDGE TABLE STARTS HERE
    public function update(Request $request, TableKnowledge $tableData) {

        $tableData->update(['content' => $request->content]);
        return response()->json(['success' => true]);

    }

    public function delete($id) {

        $tableData = TableKnowledge::findOrFail($id);
        $tableData->delete();

        return response()->json(['success' => true]);

    }

    public function create(Request $request) {

        $teacherID = Auth::id();
        
        $tableData = TableKnowledge::create([
            'teacher_id' => $teacherID,
            'room_id' => $request->room_id,
            'student_room_id' => $request->room_studentID,
            'student_name' => $request->student_name,
            'row' => $request->row,
            'column' => $request->column,
            'content' => $request->content,
        ]);
                
        return response()->json(['id' => $tableData->id], 200);
                
    }
    // KNOWLEDGE TABLE ENDS HERE


    // RECORD TABLE STARTS HERE
    public function updateSkills(Request $request, TableSkills $tableData) {

        $tableData->update(['content' => $request->content]);
        return response()->json(['success' => true]);

    }

    public function deleteSkills(Request $request, $id) {

        $tableId = $request->input('tableId');
    
        // Define the mapping of table IDs to their corresponding models
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
            $cell = $model::findOrFail($id);
            $cell->delete();
    
            return response()->json(['message' => 'Cell deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting cell: ' . $e->getMessage()], 500);
        }

    }

    public function deleteFormula(Request $request, $formulaId) {

        $tableId = $request->input('tableId');
        $roomId = $request->input('roomId');
        $formula = $request->input('formula');
        $userId = Auth::id();
    
        // Define the mapping of table IDs to their corresponding models
        // $tableModels = [
        //     'main-table' => TableSkills::class,
        //     'attendance-table' => TableAttendance::class,
        //     'rating-table' => TableRatings::class
        // ];
        
        // if (!array_key_exists($tableId, $tableModels)) {
        //     return response()->json(['message' => 'Invalid table ID.'], 400);
        // }
    
        // $model = $tableModels[$tableId];
    
        $deleted = TableFormula::where('table_id', $tableId)
        ->where('teacher_id', $userId)
        ->where('room_id', $roomId)
        ->where('column', $formulaId)
        ->where('formula', $formula)
        ->delete();

        // Check if any row was actually deleted
        if ($deleted) {
            return response()->json(['message' => 'Formula deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'No matching formula found to delete.'], 404);
        }

    }

    public function createSkills(Request $request) {
        $teacherID = Auth::id();
        $tableId = $request->input('tableId');
    
        $tableModels = [
            'main-table' => TableSkills::class,
            'attendance-table' => TableAttendance::class,
            'rating-table' => TableRatings::class
        ];
    
        // Log the incoming request data
        Log::info('Received createSkills request:', $request->all());
    
        if (!array_key_exists($tableId, $tableModels)) {
            Log::error('Invalid table ID:', ['tableId' => $tableId]);
            return response()->json(['message' => 'Invalid table ID.'], 400);
        }
    
        $model = $tableModels[$tableId];
    
        try {
            $tabledata = $model::create([
                // 'id' => $request->uniqueId,
                'teacher_id' => $teacherID,
                'room_id' => $request->room_id,
                'student_name' => $request->student_name,
                'row' => $request->row,
                'column' => $request->column,
                'content' => $request->content,
                'rowspan' => $request->rowspan,
                'colspan' => $request->colspan,
            ]);
    
            Log::info('Skill created successfully:', ['id' => $tabledata->id]);
            return response()->json(['id' => $tabledata->id], 200);
        } catch (\Exception $e) {
            Log::error('Error creating skill:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error Content: ' . $e->getMessage()], 500);
        }       
    }
    



    // public function createCalculation(Request $request) {
    //     $teacherID = Auth::id();
    //     $dataPerform = $request->input('dataPerform');
    
    //     // Check if dataPerform is not empty and contains valid data
    //     if (empty($dataPerform) || !isset($dataPerform[0]['tableId'])) {
    //         Log::error('Invalid dataPerform or missing tableId');
    //         return response()->json(['message' => 'Invalid data or missing tableId.'], 400);
    //     }
    
    //     $tableId = $dataPerform[0]['tableId'];
    //     $room_id = $dataPerform[0]['room_id'];
    
    //     $tableModels = [
    //         'main-table' => TableSkills::class,
    //         'attendance-table' => TableAttendance::class,
    //         'rating-table' => TableRatings::class
    //     ];
    
    //     if (!array_key_exists($tableId, $tableModels)) {
    //         Log::error('Invalid table ID:', ['tableId' => $tableId]);
    //         return response()->json(['message' => 'Invalid table ID.'], 400);
    //     }
    
    //     $model = $tableModels[$tableId];
    
    //     try {
    //         // Initialize an array to store inserted IDs
    //         $insertedIds = [];
    
    //         DB::transaction(function () use ($model, $dataPerform, $teacherID, &$insertedIds) {
    //             foreach ($dataPerform as $row) {
    //                 $insertedIds[] = $model::create([
    //                     'teacher_id' => $teacherID,
    //                     'room_id' => $row['room_id'],
    //                     'student_name' => $row['student_name'],
    //                     'row' => $row['row'],
    //                     'column' => $row['column'],
    //                     'content' => $row['content'],
    //                     'rowspan' => $row['rowspan'],
    //                     'colspan' => $row['colspan'],
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ])->id;  // Capture the inserted ID
    //             }
    //         });
    
    //         // Fetch the inserted rows using the collected IDs
    //         $savedData = $model::whereIn('id', $insertedIds)->get(['id', 'column', 'row']);
    
    //         Log::info('Skills created successfully:', ['ids' => $savedData]);
    //         return response()->json(['ids' => $savedData], 200);
    
    //     } catch (\Exception $e) {
    //         Log::error('Error creating skill:', ['error' => $e->getMessage()]);
    //         return response()->json(['message' => 'Error Content: ' . $e->getMessage()], 500);
    //     }
    // }
    
    public function createCalculation(Request $request)
    {
        $teacherID = Auth::id();
        $dataPerform = $request->input('dataPerform');

        // Check if dataPerform is not empty and contains valid data
        if (empty($dataPerform) || !isset($dataPerform[0]['tableId'])) {
            Log::error('Invalid dataPerform or missing tableId');
            return response()->json(['message' => 'Invalid data or missing tableId.'], 400);
        }

        $tableId = $dataPerform[0]['tableId'];
        $room_id = $dataPerform[0]['room_id'];

        $tableModels = [
            'main-table' => TableSkills::class,
            'attendance-table' => TableAttendance::class,
            'rating-table' => TableRatings::class
        ];

        if (!array_key_exists($tableId, $tableModels)) {
            Log::error('Invalid table ID:', ['tableId' => $tableId]);
            return response()->json(['message' => 'Invalid table ID.'], 400);
        }

        $model = $tableModels[$tableId];

        try {
            // Initialize an array to store inserted or updated IDs
            $insertedOrUpdatedIds = [];

            DB::transaction(function () use ($model, $dataPerform, $teacherID, &$insertedOrUpdatedIds) {
                foreach ($dataPerform as $row) {
                    // Check if a record with the same teacher_id, room_id, row, and column already exists
                    $existingCell = $model::where('teacher_id', $teacherID)
                        ->where('room_id', $row['room_id'])
                        ->where('row', $row['row'])
                        ->where('column', $row['column'])
                        ->first();

                    if ($existingCell) {
                        // If the record exists, update it
                        $existingCell->update([
                            'student_name' => $row['student_name'],
                            'content' => $row['content'],
                            'rowspan' => $row['rowspan'],
                            'colspan' => $row['colspan'],
                            'updated_at' => now(),
                        ]);
                        // Store the updated ID
                        $insertedOrUpdatedIds[] = $existingCell->id;
                    } else {
                        // If the record does not exist, create a new one
                        $newCell = $model::create([
                            'teacher_id' => $teacherID,
                            'room_id' => $row['room_id'],
                            'student_name' => $row['student_name'],
                            'row' => $row['row'],
                            'column' => $row['column'],
                            'content' => $row['content'],
                            'rowspan' => $row['rowspan'],
                            'colspan' => $row['colspan'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        // Store the new ID
                        $insertedOrUpdatedIds[] = $newCell->id;
                    }
                }
            });

            // Fetch the inserted or updated rows using the collected IDs
            $savedData = $model::whereIn('id', $insertedOrUpdatedIds)->get(['id', 'column', 'row']);

            Log::info('Skills created or updated successfully:', ['ids' => $savedData]);
            return response()->json(['ids' => $savedData], 200);

        } catch (\Exception $e) {
            Log::error('Error creating or updating skill:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error Content: ' . $e->getMessage()], 500);
        }
    }



    public function createFormula(Request $request) {
        $teacherID = Auth::id();
    
        try {
            $tabledata = TableFormula::create([
                'table_id' => $request->tableId,
                'teacher_id' => $teacherID,
                'room_id' => $request->room_id,
                'column' => $request->column,
                'formula' => $request->formula,
            ]);
    
            return response()->json(['formula' => $tabledata->formula, 'column' => $tabledata->column], 200);
        } catch (\Exception $e) {
            Log::error('Error creating skill:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error Content: ' . $e->getMessage()], 500);
        }       
    }


    // ADD STUDENT
    // public function addStudent(Request $request) {

    //     $fields = $request->validate([
    //         'studentId' => ['nullable'],
    //         'roomId' => ['required'],
    //         'studentName' => ['required']
    //     ]);
    
    //     try {
    //         StudentNames::create([
    //             'room_id' => $fields['roomId'],
    //             'name_2' => $fields['studentId'],
    //             'name_3' => $fields['studentName'],
    //         ]);
    
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Error Content: ' . $e->getMessage()], 500);
    //     }       
    // }
    
    

    // public function saveAllData(Request $request)
    // {
    //     $teacherID = Auth::id();
    //     $tableModels = [
    //         'main-table' => TableSkills::class,
    //         'attendance-table' => TableAttendance::class
    //     ];
    
    //     $data = $request->all();
    //     Log::info('Received data:', $data); // Log incoming data for debugging
    
    //     foreach ($data as $item) {
    //         $tableId = $item['tableId'];
    
    //         if (!array_key_exists($tableId, $tableModels)) {
    //             return response()->json(['message' => 'Invalid table ID.'], 400);
    //         }
    
    //         $model = $tableModels[$tableId];
    
    //         try {
    //             if (isset($item['id']) && !empty($item['id'])) {
    //                 $model::where('id', $item['id'])->update([
    //                     'teacher_id' => $teacherID,
    //                     'room_id' => $item['room_id'],
    //                     'student_room_id' => $item['room_studentID'],
    //                     'student_name' => $item['student_name'],
    //                     'row' => $item['row'],
    //                     'column' => $item['column'],
    //                     'content' => $item['content'],
    //                     'rowspan' => $item['rowspan'],
    //                     'colspan' => $item['colspan'],
    //                 ]);
    //             } else {
    //                 $model::create([
    //                     'id' => $item['uniqueId'],
    //                     'teacher_id' => $teacherID,
    //                     'room_id' => $item['room_id'],
    //                     'student_room_id' => $item['room_studentID'],
    //                     'student_name' => $item['student_name'],
    //                     'row' => $item['row'],
    //                     'column' => $item['column'],
    //                     'content' => $item['content'],
    //                     'rowspan' => $item['rowspan'],
    //                     'colspan' => $item['colspan'],
    //                 ]);
    //             Log::info('Saving data:');
    //             }
    //         } catch (\Exception $e) {
    //             Log::error('Error saving data:', ['item' => $item, 'error' => $e->getMessage()]);
    //             return response()->json(['message' => 'Error Content: ' . $item['tableId'] . ' - ' . $e->getMessage()], 500);
    //         }
    //     }
    
    //     return response()->json(['success' => true], 200);
    // }
    
    // RECORD TABLE ENDS HERE

    // ATTITUDE TABLE STARTS HERE
    public function updateAttitude(Request $request, TableAttitude $tableData) {

        $tableData->update(['content' => $request->content]);
        return response()->json(['success' => true]);

    }

    public function deleteAttitude($id) {

        $tableData = TableAttitude::findOrFail($id);
        $tableData->delete();

        return response()->json(['success' => true]);

    }

    public function createAttitude(Request $request) {

        $teacherID = Auth::id();
        
        $tableData = TableAttitude::create([
            'teacher_id' => $teacherID,
            'room_id' => $request->room_id,
            'student_room_id' => $request->room_studentID,
            'student_name' => $request->student_name,
            'row' => $request->row,
            'column' => $request->column,
            'content' => $request->content,
        ]);
                
        return response()->json(['id' => $tableData->id]);
                
    }
    // ATTITUDE TABLE ENDS HERE







    // public function processPastedText(Request $request)
    // {
    //     // Validate the request to ensure text is provided
    //     $request->validate([
    //         'pasted_text' => 'required|string',
    //     ]);
    
    //     // Get the pasted text
    //     $pastedText = $request->input('pasted_text');
    
    //     // Process the pasted text
    //     $nameRows = [];
    //     $lines = explode("\n", trim($pastedText));
    //     foreach ($lines as $line) {
    //         // Handle names with complex structures including multiple occurrences of names or initials
    //         preg_match_all('/(?:[A-Z]+(?:[- ][A-Z]+)*, [A-Z]+(?: [A-Z]+)+)(?: [A-Z])*\.?|(\S+)/', $line, $matches);
    //         $nameColumns = array_filter($matches[0], fn($value) => !is_null($value) && $value !== ''); // Filter out empty values
    
    //         // Ensure there are exactly 3 columns
    //         while (count($nameColumns) < 3) {
    //             $columns[] = ''; // Add empty strings if there are less than 3 columns
    //         }
    //         $nameRows[] = array_slice($nameColumns, 0, 3); // Limit to 3 columns if there are more
    //     }
    
    //     // Pass the rows to a view
    //     return view('records.sample', compact('nameRows'));
    // }

    // UPDATE STUDENT NAMES
    // public function updateStudentName(Request $request, TableSkills $tableData) {

    //     $tableData->update(['content' => $request->content]);
    //     return response()->json(['success' => true]);

    // }
}

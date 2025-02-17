<?php

namespace App\Http\Controllers;

use App\Models\StudentNames;
use App\Models\TableBorder;
use App\Models\TableBorderAllCell;
use App\Models\TableColor;
use App\Models\TableDoneCheck;
use App\Models\TableFontStyle;
use App\Models\TableSelectedRowCol;
use App\Models\TableTotalRowCol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Prompts\Table;

class TableButtonsController extends Controller
{
    public function saveBorders(Request $request)
    {
        try {
            $borders = $request->input('borders');
    
            foreach ($borders as $border) {

                $existingBorder = TableBorderAllCell::where('table_id', $border['table_id'])
                    ->where('room_id', $border['room_id'])
                    ->where('row', $border['row'])
                    ->where('column', $border['column'])
                    ->first();

                if($existingBorder){
                    $existingBorder->update($border);
                    // return response()->json(['message' => 'Border Already Exist!']);
                } else {
                    TableBorderAllCell::create($border);
                    
                }
    
            }

            return response()->json(['message' => 'Successfully Saved!']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to save borders', 'error' => $e->getMessage()], 500);
        }
    }

    public function getBorders($id, $tableID)
    {
        $borderAllCell = TableBorderAllCell::where('room_id', $id)->where('table_id', $tableID)->get();

        if ($borderAllCell->isEmpty()) {
            // No data found, return 204 No Content or 404 Not Found
            return response()->json(['message' => 'No borders found.'], 204);
        }

        return response()->json(['bordersAllCell' => $borderAllCell, 'tableID' => $tableID]);
    }

    // public function deleteBorders(Request $request)
    // {
    //     try {
    //         $borders = $request->input('borders');

    //         foreach ($borders as $border) {
    //             // Find the existing border record by table_id, row, and column
    //             $existingBorder = TableBorder::where('table_id', $border['table_id'])
    //                 ->where('row', $border['row'])
    //                 ->where('column', $border['column'])
    //                 ->first();

    //             if ($existingBorder) {
    //                 // Delete the existing border record
    //                 $existingBorder->delete();
    //             }
    //         }

    //         return response()->json(['borders' => $borders]);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Failed to delete borders', 'error' => $e->getMessage()], 500);
    //     }
    // }

    // DELETING ALLL BORDERS
    public function deleteBordersAllCells(Request $request)
    {
        try {
            $borders = $request->input('borders');

            foreach ($borders as $border) {
                TableBorderAllCell::where('table_id', $border['table_id'])
                    ->where('room_id', $border['room_id'])
                    ->where('row', $border['row'])
                    ->where('column', $border['column'])
                    ->delete();
            }

            return response()->json(['borders' => $borders]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete borders', 'error' => $e->getMessage()], 500);
        }
    }

    public function pushNames(Request $request)
    {
        // Fetch all student names from the StudentNames table
        try {
            $userID = Auth::id();
            $startRow = $request->input('start_row');
            $startColumn = $request->input('start_column');
            $roomId = $request->input('room_id');
            $tableId = $request->input('table_id');

            $data = [
                'table_id' => $tableId,
                'teacher_id' => $userID,
                'room_id' => $roomId,
                'row' => $startRow,
                'column' => $startColumn
            ];

            TableSelectedRowCol::create($data);

            $studentNames = StudentNames::where('room_id', $roomId)->orderBy('name_3')->get(); 

            return response()->json(['names' => $studentNames]);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to save borders', 'error' => $e->getMessage()], 500); 
        }

    }

    public function deletePushNames(Request $request, $id) {
        try {

            $tableID = $request->input('tableID');

            TableSelectedRowCol::where('room_id', $id)->where('table_id', $tableID)->delete();

            return redirect()->back();

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to Delete Record', 'error' => $e->getMessage()], 500); 
        }
    }

    // SAVING BORDERS BASE ON POSITION
    public function saveBordersTopRightBottomLeft(Request $request){

        try {
            $bordersPosition = $request->input('borders');

            foreach($bordersPosition as $position) {

                $existingPosition = TableBorderAllCell::where('table_id', $position['table_id'])
                ->where('room_id', $position['room_id'])
                ->where('row', $position['row'])
                ->where('column', $position['column'])
                ->where('isTop', $position['isTop'])
                ->where('isBottom', $position['isBottom'])
                ->where('isLeft', $position['isLeft'])
                ->where('isRight', $position['isRight'])
                ->first();

                if($existingPosition){
                    return response()->json(['message' => 'Border Already Exist!']);
                } else {
                    TableBorderAllCell::create($position);
                }
            }

            return response()->json(['message' => 'Successfully Saved!']);

        } catch (\Throwable $e) {

            return response()->json(['message' => 'Failed to Save Borders', 'error' => $e->getMessage()], 500); 

        }

    }

    // SAVING THE UNDERLINED FONTS OR TEXT
    public function saveUnderline(Request $request) {

        try {
            $fontStyle = $request->input('fontStyles');

            foreach($fontStyle as $text) {
                $existingRecord = TableFontStyle::where('table_id', $text['table_id'])
                ->where('room_id', $text['room_id'])
                ->where('row', $text['row'])
                ->where('column', $text['column'])
                ->first();
            
                if ($existingRecord) {
                    // Update the existing record
                    if($text['style'] == 'Bold' || 'Italic' || 'Underline') {
                        TableFontStyle::create($text);
                    } else {
                        $existingRecord->update($text);
                    }
                } else {
                    // Create a new record if none exists
                    TableFontStyle::create($text);
                }
            }

            return response()->json(['message' => 'Successfully Saved!']);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to Save Borders', 'error' => $e->getMessage()], 500); 
        }

    }

    public function getStyles($id, $tableID)
    {
        $stylesAllCell = TableFontStyle::where('room_id', $id)->where('table_id', $tableID)->get();

        if ($stylesAllCell->isEmpty()) {
            // No data found, return 204 No Content or 404 Not Found
            return response()->json(['message' => 'No styles found.'], 204);
        }

        return response()->json(['stylesAllCell' =>$stylesAllCell, 'tableID' => $tableID]);
    }

    public function deleteFontStyle(Request $request) {
        try {
            $fontStyle = $request->input('fontStyles');

            foreach ($fontStyle as $style) {
                TableFontStyle::where('table_id', $style['table_id'])
                    ->where('room_id', $style['room_id'])
                    ->where('row', $style['row'])
                    ->where('column', $style['column'])
                    ->delete();
            }

            return response()->json(['fontStyle' => $fontStyle]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete borders', 'error' => $e->getMessage()], 500);
        }
    }

    // SAVING COLOR
    public function saveColors(Request $request) {

        try {
            $colors = $request->input('colors');

            foreach($colors as $color) {
                $existingRecord = TableColor::where('table_id', $color['table_id'])
                ->where('room_id', $color['room_id'])
                ->where('row', $color['row'])
                ->where('column', $color['column'])
                ->where('type', $color['type'])
                ->first();

                if($existingRecord) {
                    $existingRecord->update($color);
                } else {
                    TableColor::create($color);
                }
            }

            return response()->json(['message' => 'Successfully Saved!']);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error in Saving Colors!', 'error' => $e->getMessage()], 500);
        }

    }

    public function getColors($id, $tableID)
    {
        $getColors = TableColor::where('room_id', $id)->where('table_id', $tableID)->get();

        if ($getColors->isEmpty()) {
            return response()->json(['message' => 'No colors found.'], 204);
        }

        return response()->json(['getColors' =>$getColors, 'tableID' => $tableID]);
    }

    public function deleteColorssAllCells(Request $request) {

        try {

            $colors = $request->input('colors');
            

            foreach($colors as $color) {
                TableColor::where('table_id', $color['table_id'])
                ->where('room_id', $color['room_id'])
                ->where('row', $color['row'])
                ->where('column', $color['column'])
                ->where('type', $color['type'])
                ->delete();
            }

            return response()->json(['getColors' => $colors]);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to delete colors', 'error' => $e->getMessage()], 500);
        }

    }

    // Submitting Total Rows and Cols
    public function totalRowsCols(Request $request, $id)
    {
        $userId = Auth::id();
        $tableId = $request->input('key');
        $decodeId = HelperFunctions::base64_url_decode($id);
        
        $request->validate([
            'columnNumber' => 'required|integer',
            'rowNumber' => 'required|integer'
        ]);
    
        // Check if a record with the same room_id exists
        $existingRecord = TableTotalRowCol::where('table_id', $tableId)->where('room_id', $decodeId)->first();
    
        if ($existingRecord) {
            $existingRecord->update([
                'total_row' => $request->input('rowNumber'),
                'total_column' => $request->input('columnNumber')
            ]);
        } else {
            TableTotalRowCol::create([
                'table_id' => $tableId,
                'teacher_id' => $userId,
                'room_id' => $decodeId,
                'total_row' => $request->input('rowNumber'),
                'total_column' => $request->input('columnNumber')
            ]);
        }
    
        return redirect()->back();
    }
    
    // Done Checking
    public function doneCheck(Request $request, $id)
    {
        try {
            $userId = Auth::id();
            $dataReceive = $request->input('dataPerform'); // Directly get the JSON payload
    
            if (!isset($dataReceive['tableId']) || !isset($dataReceive['column'])) {
                return response()->json(['message' => 'Invalid data format'], 400);
            }

            $existingRecord = TableDoneCheck::where('table_id', $dataReceive['tableId'])
                ->where('room_id', $id)
                ->where('column', $dataReceive['column'])
                ->first();
    
            if($existingRecord) {
                TableDoneCheck::where('table_id', $dataReceive['tableId'])
                    ->where('teacher_id', $userId)
                    ->where('room_id', $id)
                    ->where('column', $dataReceive['column'])
                    ->delete();
            } else {
                TableDoneCheck::create([
                    'table_id' => $dataReceive['tableId'],
                    'teacher_id' => $userId,
                    'room_id' => $id,
                    'column' => $dataReceive['column'],
                ]);
            }
    
            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to save data', 'error' => $e->getMessage()], 500);
        }
    }
    
}

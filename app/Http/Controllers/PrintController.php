<?php

namespace App\Http\Controllers;

use App\Models\StudentNames;
use App\Models\TableFormula;
use App\Models\TableSelectedRowCol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PrintController extends Controller
{
    public function show()
    {
        $data = ['title' => 'Welcome to the Printable Page'];
        return view('navigations.print', $data);
    }


    // IMPORTING FUNCTIONS STARTS HERE!!!!!!!!!!!!!!!!!!!!
    public function getHighlightedCells($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highlightedCells = [];
        $hiddenCells = [];
        $mergedCellsMap = [];
        $allFormula = [];
    
        // Precompute merged cells and their ranges
        foreach ($sheet->getMergeCells() as $range) {
            [$startCoord, $endCoord] = explode(':', $range);
    
            $startCol = preg_replace('/\d/', '', $startCoord); // Extract column as string
            $startRow = (int)preg_replace('/[A-Z]/i', '', $startCoord); // Extract row
            $endCol = preg_replace('/\d/', '', $endCoord);
            $endRow = (int)preg_replace('/[A-Z]/i', '', $endCoord);
    
            $rowspan = $endRow - $startRow + 1;
            $colspan = Coordinate::columnIndexFromString($endCol) - Coordinate::columnIndexFromString($startCol) + 1;
    
            // Add merged range details
            $mergedCellsMap[$range] = [
                'rowspan' => $rowspan,
                'colspan' => $colspan,
                'startRow' => $startRow,
                'startCol' => $startCol,
                'endRow' => $endRow,
                'endCol' => $endCol,
            ];
    
            // Mark all cells in the range except the top-left as hidden cells
            for ($row = $startRow; $row <= $endRow; $row++) {
                for ($col = Coordinate::columnIndexFromString($startCol); $col <= Coordinate::columnIndexFromString($endCol); $col++) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $coord = $colLetter . $row;
    
                    if ($coord !== $startCoord) { // Exclude the top-left cell
                        $hiddenCells[$coord] = [
                            'row' => $row,
                            'col' => $colLetter,
                            'main_cell' => $startCoord, // Reference the main cell
                        ];
                    }
                }
            }
        }
    
        // Iterate through each cell in the sheet
        foreach ($sheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $cellCoord = $cell->getCoordinate();
                $style = $sheet->getStyle($cellCoord);
                $fill = $style->getFill();
    
                // Check if the cell has a background color (highlighted)
                if ($fill->getFillType() !== \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE) {
                    // $value = $cell->getValue();

                    if ($cell->isFormula()) {
                        // If it's a formula, get the calculated value
                        $value = $cell->getCalculatedValue();
                        $formula = $cell->getValue();
                    } else {
                        // If it's not a formula, get the raw value
                        $value = $cell->getValue();
                    }
    
                    // Get row and column indices
                    $rowIdx = $cell->getRow();
                    $colIdx = $cell->getColumn(); // Keep column as a string (e.g., 'A', 'B')
    
                    // Default rowspan and colspan
                    $rowspan = 1;
                    $colspan = 1;
    
                    // Check merged cell map for rowspan/colspan
                    $isMainCell = true;
                    foreach ($mergedCellsMap as $range => $dimensions) {
                        if (
                            $rowIdx >= $dimensions['startRow'] && $rowIdx <= $dimensions['endRow'] &&
                            $colIdx >= $dimensions['startCol'] && $colIdx <= $dimensions['endCol']
                        ) {
                            $rowspan = $dimensions['rowspan'];
                            $colspan = $dimensions['colspan'];
                            $isMainCell = !isset($hiddenCells[$cellCoord]); // Hidden cells are not main cells
                            break;
                        }
                    }

                    if ($cell->isFormula()) {
                        $allFormula[] = [
                            'value' => $formula,
                            'table_id' => 'main-table',
                            'column' => $colIdx,
                            'row' => $rowIdx,
                        ];
                    }
    
                    // Separate main cells and hidden cells
                    if ($isMainCell) {
                        $highlightedCells[] = [
                            'value' => $value,
                            'row' => $rowIdx,
                            'col' => $colIdx,
                            'rowspan' => $rowspan,
                            'colspan' => $colspan,
                        ];
                    }
                }
            }
        }
    
        // Convert hiddenCells map to an array
        $hiddenCellsData = [];
        foreach ($hiddenCells as $coord => $hiddenCell) {
            $hiddenCellsData[] = [
                'value' => $value,
                'row' => $hiddenCell['row'],
                'col' => $hiddenCell['col'],
                'rowspan' => $rowspan,
                'colspan' => $colspan,
                'main_cell' => $hiddenCell['main_cell'], // Reference to the main cell
            ];
        }
    
        return [
            'main_cells' => $highlightedCells,
            'hidden_cells' => $hiddenCellsData,
            'formula_cells' => $allFormula
        ];
    }
    
    public function importHighlighted(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
        
            $file = $request->file('file');
            $filePath = $file->getPathName();

            // $columnNameStart = $request->input('inputColumnName');
            // $columnStart = $request->input('inputColumn');
            // $rowStart = $request->input('inputRow');
        
            $cellData = $this->getHighlightedCells($filePath);

            $userID = Auth::id();
            $userName = auth()->user()->username;
        
            $rowNames = [];
            $rowIds = [];
            $masterList = [];
            $rowStart = null;
            $numberRows = 1;
            

            foreach ($cellData['main_cells'] as $cell) {
                // Initialize arrays to keep track of rows
            
                // Check if the value matches a name
                if (preg_match('/^([A-Za-z]+, [A-Za-z]+ [A-Z]\.|[A-Za-z]+ [A-Za-z]+, [A-Za-z]+ [A-Z]\.)$/', trim($cell['value']))) {
                    // Format the name to add a period to single-letter initials
                    $formattedName = $this->formatName($cell['value']);
                
                    $rowNames[$cell['row']] = $formattedName;
                    $masterList[$cell['row']]['name_3'] = $formattedName;
                
                    if ($rowStart === null) {
                        $rowStart = $cell['row']; // Get the row of the first name
                    }
                }
                
            
                // Check if the value matches an ID
                if (preg_match('/^\d{4}-\d{4,5}-[A-Z]$/', $cell['value'])) {
                    $rowIds[$cell['row']] = $cell['value'];
                    $masterList[$cell['row']]['name_2'] = $cell['value'];
                }
            }
            
            // Save rows into the database
            foreach ($masterList as $values) {
                StudentNames::create([
                    'room_id' => $id,
                    'name_1' => $numberRows++,
                    'name_2' => $values['name_2'] ?? null, // Save ID if exists
                    'name_3' => $values['name_3'] ?? null, // Save Name if exists
                ]);
            }
            
            

            $firstCell = reset($cellData['main_cells']); // Get the first element
            if ($firstCell) {
                TableSelectedRowCol::create([
                    'table_id' => 'main-table',
                    'teacher_id' => $userID,
                    'room_id' => $id,
                    'row' =>  $rowStart,
                    'column' => Coordinate::columnIndexFromString($firstCell['col']) - 1
                ]);
            }


            // Access the first row of $cellData['formula_cells']
            foreach ($cellData['formula_cells'] as $cell) {
                // Check if the cell is in the first row
                if ($cell['row'] === $rowStart) {
                    $formula = $cell['value'];
            
                    // Check if the formula starts with =IF
                    if (str_starts_with($formula, '=IF')) {
                        $formula = preg_replace('/^=IF/', 'if', $formula); // Change =IF to if
                        $formula = str_replace('"', '', $formula); // Remove double quotes
                    }
            
                    // Replace cell references (e.g., A1, B2) with column letters only
                    $formula = preg_replace('/([A-Za-z])\d+/', '$1', $formula);
            
                    TableFormula::create([
                        'table_id' => $cell['table_id'],
                        'teacher_id' => $userID,
                        'room_id' => $id,
                        'column' => $cell['column'],
                        'formula' => $formula
                    ]);
                }
            }
            
                    

            foreach ($cellData['main_cells'] as $cell) {

                $studentName = isset($rowNames[$cell['row']]) ? $rowNames[$cell['row']] : $userName;
                $studentIds = isset($rowIds[$cell['row']]) ? ($rowIds[$cell['row']]) : null;
            
                if($studentName !== $cell['value'] && $studentIds !== $cell['value']) {         
                    DB::table('skills')->insert([
                        'teacher_id' => $userID,
                        'room_id' => $id,
                        'student_name' => $studentName,
                        'column' => $cell['col'],
                        'row' => $cell['row'],
                        'content' => $cell['value'],
                        'merged' => 0,
                        'rowspan' => $cell['rowspan'],
                        'colspan' => $cell['colspan'],
                    ]);
                }
            }
            
            foreach ($cellData['hidden_cells'] as $hiddenCell) {
                DB::table('table_merge')->insert([
                    'table_id' => 'main-table',
                    'teacher_id' => $userID,
                    'room_id' => $id,
                    'student_name' => $userName,
                    'column' => $hiddenCell['col'],
                    'row' => $hiddenCell['row'],
                    'content' => $hiddenCell['value'],
                    'merged' => 0,
                    'rowspan' => $hiddenCell['rowspan'],
                    'colspan' => $hiddenCell['colspan'],
                ]);
            }

        
            return back()->with('successImport', 'Highlighted data imported successfully!');

        } catch (\Exception $e) {
            return back()->with('errorImport', 'Import failed: ' . $e->getMessage());
        }

    }


    private function formatName($name)
    {
        $name = preg_replace('/\s+/', ' ', trim($name));
    
        return preg_replace_callback(
            '/\b([A-Z])\b(?!\.)/',
            function ($matches) {
                return $matches[1] . '.';
            },
            $name
        );
    }
}

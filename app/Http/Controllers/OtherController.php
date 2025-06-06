<?php

namespace App\Http\Controllers;

use App\Helpers\RatingExport;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\StudentNames;
use App\Models\TableFormula;
use App\Models\TableSelectedRowCol;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OtherController extends Controller
{
    // In TrackController.php
    public function track()
    {
        session(['has_navigated' => true]);
        $user = Auth::user();
        $role = $user->role;

        // Redirect based on user role
        switch ($role) {
            case 'Teacher':
                return redirect()->route('teacher-home');
            case 'Student':
                return redirect()->route('student-home');
            default:
                return redirect()->route('login');
        }
    }

    // In CreateRecordController.php
    // public function create()
    // {
    //     session(['has_navigated' => true]);
    //     return view('choices.create');
    // }
    

    // IMPORTING FUNCTIONS STARTS HERE!!!!!!!!!!!!!!!!!!!!
    // public function getHighlightedCells($filePath)
    // {
    //     $spreadsheet = IOFactory::load($filePath);
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $highlightedCells = [];
    //     $hiddenCells = [];
    //     $mergedCellsMap = [];
    //     $allFormula = [];
    
    //     // Precompute merged cells and their ranges
    //     foreach ($sheet->getMergeCells() as $range) {
    //         [$startCoord, $endCoord] = explode(':', $range);
    
    //         $startCol = preg_replace('/\d/', '', $startCoord); // Extract column as string
    //         $startRow = (int)preg_replace('/[A-Z]/i', '', $startCoord); // Extract row
    //         $endCol = preg_replace('/\d/', '', $endCoord);
    //         $endRow = (int)preg_replace('/[A-Z]/i', '', $endCoord);
    
    //         $rowspan = $endRow - $startRow + 1;
    //         $colspan = Coordinate::columnIndexFromString($endCol) - Coordinate::columnIndexFromString($startCol) + 1;
    
    //         // Add merged range details
    //         $mergedCellsMap[$range] = [
    //             'rowspan' => $rowspan,
    //             'colspan' => $colspan,
    //             'startRow' => $startRow,
    //             'startCol' => $startCol,
    //             'endRow' => $endRow,
    //             'endCol' => $endCol,
    //         ];
    
    //         // Mark all cells in the range except the top-left as hidden cells
    //         for ($row = $startRow; $row <= $endRow; $row++) {
    //             for ($col = Coordinate::columnIndexFromString($startCol); $col <= Coordinate::columnIndexFromString($endCol); $col++) {
    //                 $colLetter = Coordinate::stringFromColumnIndex($col);
    //                 $coord = $colLetter . $row;
    
    //                 if ($coord !== $startCoord) { // Exclude the top-left cell
    //                     $hiddenCells[$coord] = [
    //                         'row' => $row,
    //                         'col' => $colLetter,
    //                         'main_cell' => $startCoord, // Reference the main cell
    //                     ];
    //                 }
    //             }
    //         }
    //     }
    
    //     // Iterate through each cell in the sheet
    //     foreach ($sheet->getRowIterator() as $row) {
    //         foreach ($row->getCellIterator() as $cell) {
    //             $cellCoord = $cell->getCoordinate();
    //             $style = $sheet->getStyle($cellCoord);
    //             $fill = $style->getFill();
    
    //             // Check if the cell has a background color (highlighted)
    //             if ($fill->getFillType() !== \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE) {
    //                 // $value = $cell->getValue();

    //                 if ($cell->isFormula()) {
    //                     // If it's a formula, get the calculated value
    //                     $value = round($cell->getCalculatedValue()); 
    //                     $formula = $cell->getValue();
    //                 } else {
    //                     // If it's not a formula, get the raw value
    //                     $value = $cell->getValue();
    //                 }
    
    //                 // Get row and column indices
    //                 $rowIdx = $cell->getRow();
    //                 $colIdx = $cell->getColumn(); // Keep column as a string (e.g., 'A', 'B')
    
    //                 // Default rowspan and colspan
    //                 $rowspan = 1;
    //                 $colspan = 1;
    
    //                 // Check merged cell map for rowspan/colspan
    //                 $isMainCell = true;
    //                 foreach ($mergedCellsMap as $range => $dimensions) {
    //                     if (
    //                         $rowIdx >= $dimensions['startRow'] && $rowIdx <= $dimensions['endRow'] &&
    //                         $colIdx >= $dimensions['startCol'] && $colIdx <= $dimensions['endCol']
    //                     ) {
    //                         $rowspan = $dimensions['rowspan'];
    //                         $colspan = $dimensions['colspan'];
    //                         $isMainCell = !isset($hiddenCells[$cellCoord]); // Hidden cells are not main cells
    //                         break;
    //                     }
    //                 }

    //                 if ($cell->isFormula()) {
    //                     $allFormula[] = [
    //                         'value' => $formula,
    //                         'table_id' => 'main-table',
    //                         'column' => $colIdx,
    //                         'row' => $rowIdx,
    //                     ];
    //                 }
    
    //                 // Separate main cells and hidden cells
    //                 if ($isMainCell) {
    //                     $highlightedCells[] = [
    //                         'value' => $value,
    //                         'row' => $rowIdx,
    //                         'col' => $colIdx,
    //                         'rowspan' => $rowspan,
    //                         'colspan' => $colspan,
    //                     ];
    //                 }
    //             }
    //         }
    //     }
    
    //     // Convert hiddenCells map to an array
    //     $hiddenCellsData = [];
    //     foreach ($hiddenCells as $coord => $hiddenCell) {
    //         $hiddenCellsData[] = [
    //             'value' => $value,
    //             'row' => $hiddenCell['row'],
    //             'col' => $hiddenCell['col'],
    //             'rowspan' => $rowspan,
    //             'colspan' => $colspan,
    //             'main_cell' => $hiddenCell['main_cell'], // Reference to the main cell
    //         ];
    //     }
    
    //     return [
    //         'main_cells' => $highlightedCells,
    //         'hidden_cells' => $hiddenCellsData,
    //         'formula_cells' => $allFormula
    //     ];
    // }

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

            $startCol = preg_replace('/\d/', '', $startCoord);
            $startRow = (int)preg_replace('/[A-Z]/i', '', $startCoord);
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
                            'main_cell' => $startCoord,
                        ];
                    }
                }
            }
        }

        // Iterate through each cell in the sheet
        foreach ($sheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $cellCoord = $cell->getCoordinate();

                if ($cell->isFormula()) {
                    $value = number_format($cell->getCalculatedValue(), 2, '.', '');
                    $formula = $cell->getValue();
                } else {
                    $value = $cell->getValue();
                }

                $rowIdx = $cell->getRow();
                $colIdx = $cell->getColumn();
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
                        $isMainCell = !isset($hiddenCells[$cellCoord]);
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

        // Convert hiddenCells map to an array
        $hiddenCellsData = [];
        foreach ($hiddenCells as $coord => $hiddenCell) {
            $hiddenCellsData[] = [
                'value' => $value,
                'row' => $hiddenCell['row'],
                'col' => $hiddenCell['col'],
                'rowspan' => $rowspan,
                'colspan' => $colspan,
                'main_cell' => $hiddenCell['main_cell'],
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
        
            $cellData = $this->getHighlightedCells($filePath);

            $userID = Auth::id();
            $userName = auth()->user()->username;
        
            $rowNames = [];
            $rowIds = [];
            $masterList = [];
            $rowStart = null;
            $firstNameColumn = null;
            $numberRows = 1;
            

            foreach ($cellData['main_cells'] as $cell) {
                // Initialize arrays to keep track of rows
                $value = trim($cell['value']);
            
                if (preg_match('/^
                    (?:[A-Za-zÀ-ÖØ-öø-ÿ]+(?:-[A-Za-zÀ-ÖØ-öø-ÿ]+)?(?:\s[A-Za-zÀ-ÖØ-öø-ÿ]+){0,2})  # Last name (1-3 words, allows hyphens)
                    ,\s[A-Za-zÀ-ÖØ-öø-ÿ]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ]+)*                              # First name (1+ words)
                    (?:\s[A-Za-zÀ-ÖØ-öø-ÿ](?:\.)?)?                                             # Middle initial (optional, with or without a period)
                    (?:\s(JR|SR|II|III|IV))?                                                    # Suffix (optional)
                    /ix', $value)) {
            
            
            
                
                $col = $cell['col']; // Assign column inside the condition

                // Set the first name column if not already set
                if ($firstNameColumn === null) {
                    $firstNameColumn = $col;
                }

                // Extract names only from the detected first name column
                if ($col === $firstNameColumn) {
                    $formattedName = $this->formatName($value);
                    $rowNames[$cell['row']] = $formattedName;
                    $masterList[$cell['row']]['name_3'] = $formattedName;

                    if ($rowStart === null) {
                        $rowStart = $cell['row']; // Set the starting row
                    }
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
    
    
    // IMPORTING FUNCTIONS ENDS HERE!!!!!!!!!!!!!!!!!!!!


    // EXPORTING FUNCTIONS STARTS HERE!!!!!!!!!!!!!!!!!!!!
    public function exportToExcel(Request $request, $id)
    {
        $tableData = $request->input('tableData');
        $tableRating = $request->input('tableRating');
        $getSubjectCode = Rooms::where('id', $id)->first();
    
        if (!$tableData || !is_array($tableData)) {
            return response()->json(['error' => 'Invalid table data'], 400);
        }
    
        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setName('Arial');


        $classSheetTitle = substr(pathinfo($getSubjectCode->section, PATHINFO_FILENAME), 0, 31);
        $sheet->setTitle($classSheetTitle);
    
        foreach ($tableData as $rowIndex => $row) {
            foreach ($row as $colIndex => $cellData) {
                // Calculate the cell's Excel address based on its column and row
                $excelColumn = $cellData['cellColumn'] ?? \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $excelRow = $cellData['cellRow'] ?? ($rowIndex + 1);
                $cellAddress = trim($excelColumn) . trim($excelRow);
    
                if (is_array($cellData)) {
                    // Retrieve cell data
                    $value = $cellData['value'] ?? '';
                    $formula = $cellData['formula'] ?? null;
                    $rowspan = $cellData['rowspan'] ?? 1;
                    $colspan = $cellData['colspan'] ?? 1;
                    $bold = $cellData['bold'] ?? false;
                    $italic = $cellData['italic'] ?? false;
                    $underline = $cellData['underline'] ?? false;
                    $align = $cellData['align'] ?? 'left';
    

                    // $sheet->setCellValue('F3', ' UPDATED VALUE');
                    
                    // Set value or formula
                    if ($formula) {
                        $sheet->setCellValue($cellAddress, $formula);

                        $sheet->getStyle($cellAddress)->getNumberFormat()->setFormatCode('#,##0');
                    } else {
                        $sheet->setCellValue($cellAddress, $value);
                    }
    
                    // Apply text formatting
                    $styleArray = [];
                    if ($bold) {
                        $styleArray['font']['bold'] = true;
                    }
                    if ($italic) {
                        $styleArray['font']['italic'] = true;
                    }
                    if ($underline) {
                        $styleArray['font']['underline'] = Font::UNDERLINE_SINGLE;
                    }
    
                    // Apply text alignment
                    switch ($align) {
                        case 'center':
                            $styleArray['alignment']['horizontal'] = Alignment::HORIZONTAL_CENTER;
                            break;
                        case 'right':
                            $styleArray['alignment']['horizontal'] = Alignment::HORIZONTAL_RIGHT;
                            break;
                        default:
                            $styleArray['alignment']['horizontal'] = Alignment::HORIZONTAL_LEFT;
                            break;
                    }
    
                    // Apply the styles to the cell
                    if (!empty($styleArray)) {
                        $sheet->getStyle($cellAddress)->applyFromArray($styleArray);
                    }
    
                    // Handle rowspan and colspan (merge cells)
                    if ($rowspan > 1 || $colspan > 1) {
                        $endColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($excelColumn) + $colspan - 1
                        );
                        $endRowNumber = $excelRow + $rowspan - 1;
                        $mergeRange = $cellAddress . ':' . $endColumnLetter . $endRowNumber;
                        $sheet->mergeCells($mergeRange);
                    }
                } 
            }
        }

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
        ->getFont()
        ->setName('Arial');

        foreach ($sheet->getColumnIterator() as $column) {
            $columnLetter = $column->getColumnIndex();
            $hasContent = false;
        
            // Check if the column has content
            foreach ($sheet->getRowIterator() as $row) {
                $cell = $sheet->getCell($columnLetter . $row->getRowIndex());
                if ($cell->getValue() !== null && $cell->getValue() !== '') {
                    $hasContent = true;
                    break;
                }
            }
        
            // Set narrow width if there is content
            if ($hasContent) {
                $sheet->getColumnDimension($columnLetter)->setWidth(5);
            }
        }
        
        
        



    
    // **Create Ratings Sheet*
    $ratingSheet = $spreadsheet->createSheet();
    RatingExport::ratingExport($ratingSheet, $getSubjectCode);


    $columnLabels = ['#1', 'Name', 'M.C', 'M.P', 'M.A', 'MidGr.', 'Mid.N.Eqv.', 'F.C', 'F.P', 'F.A', 'T.F.Gr.', 'F.N.Eqv.', 'Mid', 'Fin', 'FR.Eqv', 'FR.N.Eqv', 'Credits', 'Remarks', '#2'];
    $decimalColumns = ['Mid.N.Eqv.', 'F.N.Eqv.', 'FR.N.Eqv']; 

    $excelColumns = [];
    foreach ($columnLabels as $index => $label) {
        $excelColumns[$label] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
    }

    $maxRows = 30;

    $firstRow = PHP_INT_MAX;
    $lastRow = 0;
    $firstColumnIndex = PHP_INT_MAX;
    $lastColumnIndex = 0;

    foreach ($tableRating as $rowIndex => $row) {
        if ($rowIndex >= $maxRows) break; 
        foreach ($row as $colIndex => $cellData) {
            // Skip if cellData is not an array
            if (!is_array($cellData)) continue;
    
            // Ensure 'cellColumn' exists in the predefined column map
            $columnLabel = $cellData['cellColumn'] ?? null;
            if (!isset($excelColumns[$columnLabel])) continue;
    
            // Get the corresponding Excel column letter
            $excelColumn = $excelColumns[$columnLabel];
            $excelRow = $rowIndex + 11; // Adjusting base row index
            $cellAddress = $excelColumn . $excelRow;

            // Track the first and last row/column indices
            $firstRow = min($firstRow, $excelRow);
            $lastRow = max($lastRow, $excelRow);

            $columnIndex = Coordinate::columnIndexFromString($excelColumn);
            $firstColumnIndex = min($firstColumnIndex, $columnIndex);
            $lastColumnIndex = max($lastColumnIndex, $columnIndex);
    
            // Retrieve cell data
            $value = $cellData['value'] ?? '';
            $formula = $cellData['formula'] ?? null;
            $rowspan = $cellData['rowspan'] ?? 1;
            $colspan = $cellData['colspan'] ?? 1;
            $bold = $cellData['bold'] ?? false;
            $italic = $cellData['italic'] ?? false;
            $underline = $cellData['underline'] ?? false;
            $align = $cellData['align'] ?? 'left';
    
            // Apply decimal formatting for specific columns
            if (in_array($columnLabel, $decimalColumns) && is_numeric($value)) {
                $value = number_format((float) $value, 2, '.', '');
                $ratingSheet->getStyle($cellAddress)->getNumberFormat()->setFormatCode('#,##0.00');
            }
    
            // Set value or formula
            if ($formula) {
                $ratingSheet->setCellValue($cellAddress, $formula);
            } else {
                $ratingSheet->setCellValue($cellAddress, $value);
            }
    
            // Apply text formatting
            $styleArray = [];
            if ($bold) $styleArray['font']['bold'] = true;
            if ($italic) $styleArray['font']['italic'] = true;
            if ($underline) $styleArray['font']['underline'] = Font::UNDERLINE_SINGLE;
    
            // Apply text alignment
            $alignmentOptions = [
                'center' => Alignment::HORIZONTAL_CENTER,
                'right' => Alignment::HORIZONTAL_RIGHT,
                'left' => Alignment::HORIZONTAL_LEFT
            ];
            $styleArray['alignment']['horizontal'] = $alignmentOptions[$align] ?? Alignment::HORIZONTAL_LEFT;
    
            $styleArray['font']['size'] = ($excelColumn === 'B') ? 11 : 10;


            // Apply styles
            if (!empty($styleArray)) {
                $ratingSheet->getStyle($cellAddress)->applyFromArray($styleArray);
            }
    
            // Handle rowspan and colspan (merge cells)
            if ($rowspan > 1 || $colspan > 1) {
                $endColumn = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($excelColumn) + $colspan - 1);
                $endRow = $excelRow + $rowspan - 1;
                $mergeRange = "$excelColumn$excelRow:$endColumn$endRow";
                $ratingSheet->mergeCells($mergeRange);
            }
        }
    }

    if ($firstRow <= $lastRow && $firstColumnIndex <= $lastColumnIndex) {
        $firstColumnLetter = Coordinate::stringFromColumnIndex($firstColumnIndex);
        $lastColumnLetter = Coordinate::stringFromColumnIndex($lastColumnIndex);
        $borderRange = "$firstColumnLetter$firstRow:$lastColumnLetter$lastRow";
    
        // Apply border
        $ratingSheet->getStyle($borderRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    foreach ($ratingSheet->getColumnIterator() as $column) {
        $columnLetter = $column->getColumnIndex();
        $hasContent = false;
    
        // Check if the column has content
        foreach ($ratingSheet->getRowIterator() as $row) {
            $cell = $ratingSheet->getCell($columnLetter . $row->getRowIndex());
            if ($cell->getValue() !== null && $cell->getValue() !== '') {
                $hasContent = true;
                break;
            }
        }
    
        // Set column B to a width of 10
        if ($columnLetter === 'B') {
            $ratingSheet->getColumnDimension('B')->setWidth(25);
        } 
        elseif ($columnLetter === 'A') {
            $ratingSheet->getColumnDimension('A')->setWidth(3);
        }
        elseif ($columnLetter === 'S') {
            $ratingSheet->getColumnDimension('S')->setWidth(3);
        }
        elseif ($columnLetter === 'R') {
            $ratingSheet->getColumnDimension('R')->setWidth(8);
        }
        elseif ($hasContent) {
            $ratingSheet->getColumnDimension($columnLetter)->setWidth(4.2);
        }
    }

    // Footer
    RatingExport::ratingFooter($ratingSheet);
    
    
    
    



    // Applying Borders
    $borderRange = 'A9:S10';
    $ratingSheet->getStyle($borderRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // **Set active sheet back to Class Records**
    $ratingSheet->getStyle('A1:' . $ratingSheet->getHighestColumn() . $ratingSheet->getHighestRow())
    ->getFont()
    ->setName('Arial');
    $spreadsheet->setActiveSheetIndex(0);

    // **Save File**
    $filename = $getSubjectCode->class_name . '_' . now()->format('Y-m-d') . '.xlsx';
    $filePath = storage_path('app/public/' . $filename);
    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->json([
        'filename' => $filename,
        'download_url' => route('download.file', ['filename' => $filename])
    ]);
    }
    
    
    
    // EXPORTING FUNCTIONS ENDS HERE!!!!!!!!!!!!!!!!!!!!


}

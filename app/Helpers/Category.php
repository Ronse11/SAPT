<?php
namespace App\Helpers;

use App\Models\TableSkills;
use Illuminate\Support\Facades\Route;


class Category
{
    public static function organizedData($skills, $startRow, $sequences)
    {
        $organizedData = [];
        foreach ($skills as $cell) {
            $organizedData[$cell->column] = $cell;
        }

        $organizedRows = [];
        // Here we are assuming $startRow is a collection of rows
        foreach ($startRow as $row) {

            $organizedRows[] = $row;
        }

        // Determine the maximum column index that has content
        $maxColIndex = 0;
        foreach ($organizedRows as $row) {
            $column = $row->column ?? null;
            if ($column !== null) {
                $index = array_search($column, $sequences);
                if ($index !== false && $index > $maxColIndex) {
                    $maxColIndex = $index;
                }
            }
        }

        foreach ($organizedData as $column => $cell) {
            $index = array_search($column, $sequences);
            if ($index !== false && $index > $maxColIndex) {
                $maxColIndex = $index;
            }
        }

        return [
            'organizedData' => $organizedData,
            'organizedRows' => $organizedRows,
            'maxColIndex' => $maxColIndex
        ];
    }

    public static function columns()
    {
        $nextSequence = function($index) {
            if ($index < 26) {
                return chr($index + ord('A'));
            } else {
                $index -= 26;
                $firstChar = chr(floor($index / 26) + ord('A'));
                $secondChar = chr(($index % 26) + ord('A'));
                return $firstChar . $secondChar;
            }
        };

        $sequences = [];
        for ($i = 0; $i <= 104; $i++) {
            $sequences[] = $nextSequence($i);
        }

        return $sequences;
    }

    public static function getColumns($decodeID, $startingRow)
    {
        $quizKeywords = ['quiz', 'quizzes', 'test', 'exam', 'activity', 'activities'];
        $data = TableSkills::where('room_id', $decodeID)
        ->where('row', '<', $startingRow)
        ->where(function ($query) use ($quizKeywords) {
            foreach ($quizKeywords as $keyword) {
                $query->orWhere('content', 'LIKE', "%$keyword%");
            }
        })
        ->orderBy('row', 'asc')
        ->get();

        return $data;
    }

    public static function getQuizzesRelated($decodeID, $startingRow, $studentSchoolName)
    {
        $sequences = Category::columns();
        $data = Category::getColumns($decodeID, $startingRow);
    
        if (!$data || empty($data)) {
            return null;
        }

        $headerStartRow = null;
    
        $quizzesStart = [];
        $quizzesSpan = [];

        $quizzesColumns = [];
        $quizKeywords = [];
    
        foreach ($data as $value) {
            $quizzesStart[] = $value->column;
            $quizzesSpan[] = $value->colspan;
            $headerStartRow = $value->row;
        }
    
        foreach ($quizzesStart as $index => $start) {
            $startIndex = array_search($start, $sequences);
    
            // Ensure startIndex is valid and quizzesSpan exists for this index
            if ($startIndex !== false && isset($quizzesSpan[$index])) {
                $quizzesColumns[] = array_slice($sequences, $startIndex, $quizzesSpan[$index]);
            }
        }
    
        $quizzesColumns = array_merge(...$quizzesColumns);
    
        $headerWords = TableSkills::where('room_id', $decodeID)
            ->where('row', $headerStartRow)
            ->whereIn('column', $quizzesStart)
            ->get();
    
        if (!$headerWords) {
            return null;
        }
    
        foreach ($headerWords as $word) {
            if (!empty($word->content)) {
                $quizKeywords[] = $word->content;
            }
        }
        
        $hasQuizKeyword = function ($content) use ($quizKeywords) {
            if (empty($quizKeywords)) {
                return false;
            }
        
            foreach ($quizKeywords as $keyword) {
                if (stripos($content, $keyword) !== false) {
                    return true;
                }
            }
        
            return false;
        };
    
        $columnsByCategory = TableSkills::where('room_id', $decodeID)
            ->whereIn('column', $quizzesColumns)
            ->get();
    
        $processedColumns = collect($columnsByCategory)
            ->groupBy('column')
            ->map(function ($items) use ($hasQuizKeyword, $startingRow) {
                return [
                    'quizzes' => $items->filter(fn($item) => $hasQuizKeyword($item->content))->values(),
                    'date' => $items->reject(fn($item) => $hasQuizKeyword($item->content))
                            ->reject(fn($item) => $item->row >= $startingRow)
                            ->reject(fn($item) => preg_match('/^\d+%?$/', $item->content))
                            ->map(function ($item) {
                                $item->content = Category::formatDate($item->content); // Apply the function
                                return $item;
                            })
                            ->values(),
                    'total' => $items->filter(fn($item) => preg_match('/^\d+%?$/', $item->content) && $item->row < $startingRow)
                            ->values(),
                    'scores' => $items->filter(fn($item) => $item->row >= $startingRow)->values(),
                ];
            });
    
        return [
            'quizzesColumns' => $quizzesColumns,
            'processedColumns' => $processedColumns,
        ];
    }

    public static function formatDate($date) {
        // List of month names
        $months = [
            1 => "January", 2 => "February", 3 => "March", 4 => "April",
            5 => "May", 6 => "June", 7 => "July", 8 => "August",
            9 => "September", 10 => "October", 11 => "November", 12 => "December"
        ];
    
        // Remove extra spaces and normalize separators (convert . - / to space)
        $date = trim(str_replace(['.', '-', '/'], ' ', $date));
    
        // Match "April 1, 2024" or "April 1"
        if (preg_match('/^([A-Za-z]+)\s(\d{1,2})(?:,\s*(\d{2,4}))?$/', $date, $matches)) {
            $month = ucfirst(strtolower($matches[1]));
            $day = (int) $matches[2];
            $year = isset($matches[3]) ? (int) $matches[3] : null;
    
            if ($year !== null && $year < 100) {
                $year += ($year <= 50) ? 2000 : 1900; // Convert 24 → 2024, 99 → 1999
            }
    
            if (in_array($month, $months)) {
                return $year ? "{$month} {$day}, {$year}" : "{$day} {$month}";
            }
        }
    
        // Match "4 1 24" or "4 1" (interpreted as "April 1, 2024" or "April 1")
        if (preg_match('/^(\d{1,2})\s(\d{1,2})(?:\s(\d{2,4}))?$/', $date, $matches)) {
            $month = (int) $matches[1];
            $day = (int) $matches[2];
            $year = isset($matches[3]) ? (int) $matches[3] : null;
    
            if ($year !== null && $year < 100) {
                $year += ($year <= 50) ? 2000 : 1900;
            }
    
            if (isset($months[$month])) {
                return $year ? "{$months[$month]} {$day}, {$year}" : "{$day} {$months[$month]}";
            }
        }
    
        // Match "4.1.24" or similar with dots/slashes/dashes (Month.Day.Year)
        if (preg_match('/^(\d{1,2})\s(\d{1,2})\s(\d{2,4})$/', $date, $matches)) {
            $month = (int) $matches[1];
            $day = (int) $matches[2];
            $year = (int) $matches[3];
    
            if ($year < 100) {
                $year += ($year <= 50) ? 2000 : 1900;
            }
    
            if (isset($months[$month])) {
                return "{$months[$month]} {$day}, {$year}";
            }
        }
    
        // Try to parse using strtotime for standard date formats
        $timestamp = strtotime($date);
        if ($timestamp) {
            return date('F j, Y', $timestamp); // Output: "April 1, 2024"
        }
    
        return "Invalid date";
    }
    

    public static function notification($quizzesColumns, $organized, $doneCheck)
    {
        $cells = [];
        $data = [];

        $setQuizzes = collect($quizzesColumns);
        $setCheck = collect($doneCheck);

        $filteredQuizzes = $setQuizzes->filter(fn($col) => $setCheck->contains($col))->values();
        
        foreach($filteredQuizzes as $quiz) {
            $cells[] = $organized['organizedData'][$quiz] ?? null;
        }
                                                    
        foreach($cells as $cell) {
            $data[] = $cell->content ?? null;
        }

        $nullCount = count(array_filter($data, function ($value) {
            return is_null($value);
        }));

        if($nullCount >= 2) {
            $message = 'You missed 2 Quizzes';
        } else {
            $message = '';
        }

        return $message;
    }
}
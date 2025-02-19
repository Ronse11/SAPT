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

    public static function getQuizzesRelated($decodeID, $startingRow)
    {
        $sequences = Category::columns();

        $data = Category::getColumns($decodeID, $startingRow);

        if(!$data) {
            return null;
        }

        $quizzesStart = $data->column;
        $quizzesSpan = $data->colspan;
        $startIndex = array_search($quizzesStart, $sequences);
        $quizzesColumns = array_slice($sequences, $startIndex, $quizzesSpan);

        return $quizzesColumns;
    }

    public static function getColumns($decodeID, $startingRow)
    {
        $quizKeywords = ['Quiz', 'Quizzes', 'Assessment', 'Test'];
        $data = TableSkills::where('room_id', $decodeID)
        ->where('row', '<', $startingRow)
        ->where(function ($query) use ($quizKeywords) {
            foreach ($quizKeywords as $keyword) {
                $query->orWhere('content', 'LIKE', "%$keyword%");
            }
        })
        ->orderBy('row', 'asc')
        ->first();

        return $data;
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
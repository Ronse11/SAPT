<?php

namespace App\Http\Controllers;

use App\Models\StudentNames;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class PdfController extends Controller
{
    public function upload(Request $request, $id)
    {
        // Validate the uploaded file
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:2048',
        ]);
    
        // Store the uploaded file temporarily
        $filePath = $request->file('pdf_file')->store('temp');
    
        try {
            // Extract text from the PDF
            $parser = new Parser();
            $pdf = $parser->parseFile(storage_path('app/' . $filePath));
            $text = $pdf->getText();
    
            // Extract required data from the text
            $students = $this->extractStudentData($text);
    
            // Save the data to the database
            foreach ($students as $student) {
                StudentNames::create([
                    'room_id' => $id,
                    'name_2' => $student['student_id'],
                    'name_3' => $student['name']
                ]);
            }
        } finally {
            // Delete the temporary file
            if (file_exists(storage_path('app/' . $filePath))) {
                unlink(storage_path('app/' . $filePath));
            }
        }
    
        return back();
    }
    

    private function extractStudentData($text)
    {
        $lines = explode("\n", $text);
        $students = [];
    
        foreach ($lines as $line) {
            if (preg_match('/^\d+\s+(\d{4}-\d{4}-[A-Z])\s+([A-Z ,\'-]+)$/', $line, $matches)) {
                $students[] = [
                    'student_id' => $matches[1],
                    'name' => $this->formatName(trim($matches[2])), // Format name
                ];
            }
        }
    
        foreach ($lines as $line) {
            if (preg_match('/^(\d{5,6})-(\d{4})-([A-Za-z])\s+([A-Z ,\'-]+)$/', $line, $matches)) {
                $firstPart = $matches[1];  // First part (either 5 or 6 digits)
                $secondPart = $matches[2]; // The 4 digits after the first hyphen
                $letter = $matches[3];     // The letter part
                $name = $matches[4];       // The name part
    
                if (strlen($firstPart) > 4) {
                    $remainingDigits = substr((string) $firstPart, -4);
                    $formattedId = $remainingDigits . "-" . $secondPart . "-" . $letter;
                }
    
                $students[] = [
                    'student_id' => $formattedId,
                    'name' => $this->formatName($name), // Format name
                ];
            }
        }
    
        return $students;
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
    

    // private function formatName($name)
    // {
    //     // Remove multiple spaces and replace with a single space
    //     $name = preg_replace('/\s+/', ' ', $name);
    
    //     return preg_replace_callback(
    //         '/\b([A-Za-z]+)\b/', // Match each word
    //         function ($matches) {
    //             $word = $matches[1];
    //             // Add a period only to single letters (e.g., D to D.)
    //             if (strlen($word) === 1 && !str_ends_with($word, '.')) {
    //                 return $word . '.';
    //             }
    //             return $word; // Leave other words unchanged
    //         },
    //         $name
    //     );
    // }
    
}

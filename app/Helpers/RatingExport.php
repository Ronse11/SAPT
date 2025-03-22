<?php
namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;


class RatingExport {
    public static function ratingExport($ratingSheet, $getSubjectCode) 
    {
        $ratingSheetTitle = substr(pathinfo('GS'.' '.$getSubjectCode->section, PATHINFO_FILENAME), 0, 31);
        $ratingSheet->setTitle($ratingSheetTitle);
        // University Header
        $ratingSheet->mergeCells('A2:S2');
        $ratingSheet->setCellValue('A2', 'CENTRAL PHILIPPINES STATE UNIVERSITY');
        $ratingSheet->getStyle('A2')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $ratingSheet->mergeCells('A3:S3');
        $ratingSheet->setCellValue('A3', 'Kabankalan City, Negros Occidental');
        $ratingSheet->getStyle('A3')->getFont()->setSize(10);
        $ratingSheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $ratingSheet->mergeCells('A4:S4');
        $ratingSheet->setCellValue('A4', 'GRADING SHEET');
        $ratingSheet->getStyle('A4')->getFont()->setBold(true)->setSize(11);
        $ratingSheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Subject Details
        $richTextA6 = new RichText();
        $richTextA6->createText('Subject Code: ');
        $boldTextA6 = $richTextA6->createTextRun(strtoupper($getSubjectCode->class_name));
        $boldTextA6->getFont()->setBold(true)->setSize(10);
        $ratingSheet->setCellValue('A6', $richTextA6);
        
        // Descriptive Title
        $richTextG6 = new RichText();
        $richTextG6->createText('Descriptive Title: ');
        $boldTextG6 = $richTextG6->createTextRun(ucfirst($getSubjectCode->subject));
        $boldTextG6->getFont()->setBold(true)->setSize(10);
        $ratingSheet->setCellValue('G6', $richTextG6);
        
        // Course & Semester Details
        $richTextA7 = new RichText();
        $richTextA7->createText('Course/Year/Section: ');
        $boldTextA7 = $richTextA7->createTextRun(strtoupper($getSubjectCode->section));
        $boldTextA7->getFont()->setBold(true)->setSize(10);
        $ratingSheet->setCellValue('A7', $richTextA7);
        
        // Units
        $richTextG7 = new RichText();
        $richTextG7->createText('Units: ');
        $boldTextG7 = $richTextG7->createTextRun(ucfirst($getSubjectCode->units ?? ''));
        $boldTextG7->getFont()->setBold(true)->setSize(10);
        $ratingSheet->setCellValue('G7', $richTextG7);
        
        // Semester
        $richTextK7 = new RichText();
        $richTextK7->createText('Semester: ');
        $boldTextK7 = $richTextK7->createTextRun(ucfirst($getSubjectCode->semester ?? ''));
        $boldTextK7->getFont()->setBold(true)->setSize(10);
        $ratingSheet->setCellValue('K7', $richTextK7);
        
        // Instruction Note
        $ratingSheet->mergeCells('A8:S8');
        $ratingSheet->setCellValue('A8', '(Grades must be written in BLACK or BLUE ink. CONDITIONAL OR FAILURE in RED ink)');
        
        $noteStyle = $ratingSheet->getStyle('A8')->getFont();
        $noteStyle->setItalic(true)->setSize(10);
        $ratingSheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Styling BLACK, BLUE, and RED in the note
        $richText = new RichText();
        $richText->createText('(Grades must be written in ');
        $black = $richText->createTextRun('BLACK');
        $black->getFont()->setBold(true)->setColor(new Color(Color::COLOR_BLACK));
        $richText->createText(' or ');
        $blue = $richText->createTextRun('BLUE');
        $blue->getFont()->setBold(true)->setColor(new Color(Color::COLOR_BLUE));
        $richText->createText(' ink. ');
        $conditional = $richText->createTextRun('CONDITIONAL OR FAILURE');
        $conditional->getFont()->setBold(true)->setItalic(true);
        $richText->createText(' in ');
        $red = $richText->createTextRun('RED');
        $red->getFont()->setBold(true)->setColor(new Color(Color::COLOR_RED));
        $richText->createText(' ink)');
        $ratingSheet->getCell('A8')->setValue($richText);
    
        // NAMES
        $ratingSheet->mergeCells('A9:B9');
        $ratingSheet->setCellValue('A9', 'NAMES');
        $ratingSheet->getStyle('A9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('A9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $ratingSheet->mergeCells('A10:B10');
        $ratingSheet->setCellValue('A10', '(Arrange alphabetically regardless of sex)');
        $ratingSheet->getStyle('A10')->getFont()->setItalic(true)->setSize(7);
        $ratingSheet->getStyle('A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Mid-Term
        // C
        $ratingSheet->mergeCells('C9:C10');
        $ratingSheet->setCellValue('C9', 'C');
        $ratingSheet->getStyle('C9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('C9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('C9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // P
        $ratingSheet->mergeCells('D9:D10');
        $ratingSheet->setCellValue('D9', 'P');
        $ratingSheet->getStyle('D9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('D9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('D9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // A
        $ratingSheet->mergeCells('E9:E10');
        $ratingSheet->setCellValue('E9', 'A');
        $ratingSheet->getStyle('E9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('E9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('E9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // Mid
        $ratingSheet->setCellValue('F9', 'Mid');
        $ratingSheet->getStyle('F9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('F9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Gr.
        $ratingSheet->setCellValue('F10', 'Gr.');
        $ratingSheet->getStyle('F10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('F10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // N.
        $ratingSheet->setCellValue('G9', 'N.');
        $ratingSheet->getStyle('G9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('G9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Gr.
        $ratingSheet->setCellValue('G10', 'Eqv.');
        $ratingSheet->getStyle('G10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('G10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Final Term
        // C
        $ratingSheet->mergeCells('H9:H10');
        $ratingSheet->setCellValue('H9', 'C');
        $ratingSheet->getStyle('H9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('H9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('H9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // P
        $ratingSheet->mergeCells('I9:I10');
        $ratingSheet->setCellValue('I9', 'P');
        $ratingSheet->getStyle('I9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('I9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('I9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // A
        $ratingSheet->mergeCells('J9:J10');
        $ratingSheet->setCellValue('J9', 'A');
        $ratingSheet->getStyle('J9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('J9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('J9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // T.
        $ratingSheet->setCellValue('K9', 'T.');
        $ratingSheet->getStyle('K9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('K9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // F.Gr.
        $ratingSheet->setCellValue('K10', 'Gr.');
        $ratingSheet->getStyle('K10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('K10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // N.
        $ratingSheet->setCellValue('L9', 'N.');
        $ratingSheet->getStyle('L9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('L9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Gr.
        $ratingSheet->setCellValue('L10', 'Eqv.');
        $ratingSheet->getStyle('L10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('L10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Mid%
        $ratingSheet->setCellValue('M9', 'Eqv');
        $ratingSheet->getStyle('M9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('M9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // 40%
        $ratingSheet->setCellValue('M10', '40%');
        $ratingSheet->getStyle('M10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('M10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Fin%
        $ratingSheet->setCellValue('N9', 'Final');
        $ratingSheet->getStyle('N9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('N9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // 60%
        $ratingSheet->setCellValue('N10', '60%');
        $ratingSheet->getStyle('N10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('N10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // FR%
        $ratingSheet->setCellValue('O9', 'FR%');
        $ratingSheet->getStyle('O9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('O9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Eqv
        $ratingSheet->setCellValue('O10', 'Eqv.');
        $ratingSheet->getStyle('O10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('O10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // N.
        $ratingSheet->setCellValue('P9', 'N.');
        $ratingSheet->getStyle('P9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('P9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Eqv
        $ratingSheet->setCellValue('P10', 'Eqv.');
        $ratingSheet->getStyle('P10')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('P10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Credit
        $ratingSheet->mergeCells('Q9:Q10');
        $ratingSheet->setCellValue('Q9', 'Credit');
        $ratingSheet->getStyle('Q9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('Q9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('Q9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
        // Remarks
        $ratingSheet->mergeCells('R9:R10');
        $ratingSheet->setCellValue('R9', 'Remarks');
        $ratingSheet->getStyle('R9')->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle('R9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ratingSheet->getStyle('R9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $ratingSheet->mergeCells('S9:S10');
        $ratingSheet->setCellValue('S9', '');
    }

    public static function ratingFooter($ratingSheet)
    {

        $gradingStartRow = 41; // Row where grading sheet starts
        $gradingStartColumn = 'A';
        $gradingEndColumn = 'S';

        // Set the table headers
        $ratingSheet->mergeCells("A{$gradingStartRow}:S{$gradingStartRow}");
        $ratingSheet->setCellValue("A{$gradingStartRow}", "% Equivalent     No. Equivalent     1.0 - Excellent    2.0 - Thorough    3.0 - Lowest Passing Grade    5.0 - Failure");
        $ratingSheet->getStyle("A{$gradingStartRow}")->getFont()->setBold(true)->setSize(10);
        $ratingSheet->getStyle("A{$gradingStartRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $ratingSheet->mergeCells("C" . ($gradingStartRow + 2) . ":S" . ($gradingStartRow + 2));
        $ratingSheet->setCellValue("C" . ($gradingStartRow + 2), "MIDTERM");
        $ratingSheet->getStyle("C" . ($gradingStartRow + 2))->getFont()->setBold(true)->setSize(11);
        $ratingSheet->getStyle("C" . ($gradingStartRow + 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
    }
}
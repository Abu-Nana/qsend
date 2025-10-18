<?php
/**
 * Create sample PDF files for testing
 */
require_once 'vendor3/autoload.php';

use setasign\Fpdi\Fpdi;

$courses = ['ABC101', 'ABC102', 'ABC103'];

foreach ($courses as $course) {
    // Create a simple PDF
    $pdf = new Fpdi();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, "Sample Question Paper - $course", 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "This is a sample question paper for testing purposes.", 0, 1);
    $pdf->Cell(0, 10, "Course: $course", 0, 1);
    $pdf->Cell(0, 10, "Date: " . date('Y-m-d'), 0, 1);
    
    $filename = "DEASemester/$course.pdf";
    $pdf->Output($filename, 'F');
    echo "Created: $filename\n";
}

echo "Sample PDF files created successfully!\n";
?>

<?php
/**
 * Test PDF processing specifically
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include database connection
require_once __DIR__ . '/../config/database.php';
$conn = Database::getPDO();

// Check if user is logged in
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Set header for JSON response
header('Content-Type: application/json');

// Check if vendor3/autoload.php exists
$vendor_loaded = false;
if (file_exists('vendor3/autoload.php')) {
    require 'vendor3/autoload.php';
    $vendor_loaded = true;
}

try {
    echo "=== PDF PROCESSING TEST ===\n";
    
    // Test FPDI class
    if ($vendor_loaded && class_exists('setasign\Fpdi\Fpdi')) {
        echo "✅ FPDI class available\n";
        
        // Test creating a PDF
        $pdf = new \setasign\Fpdi\Fpdi();
        echo "✅ FPDI instance created\n";
        
        // Test adding a page
        $pdf->AddPage();
        echo "✅ Page added\n";
        
        // Test setting font
        $pdf->SetFont('Arial', 'B', 16);
        echo "✅ Font set\n";
        
        // Test adding content
        $pdf->Cell(0, 10, 'Test PDF', 0, 1, 'C');
        echo "✅ Content added\n";
        
        // Test output to file
        $test_file = 'temp/test_pdf_' . time() . '.pdf';
        $pdf->Output($test_file, 'F');
        echo "✅ PDF saved to: $test_file\n";
        
        if (file_exists($test_file)) {
            echo "✅ PDF file exists\n";
            unlink($test_file); // Clean up
        }
        
    } else {
        echo "❌ FPDI class not available\n";
    }
    
    // Test ZipArchive
    if (class_exists('ZipArchive')) {
        echo "✅ ZipArchive class available\n";
        
        // Test creating a ZIP file
        $zip_file = 'temp/test_zip_' . time() . '.zip';
        $zip = new ZipArchive();
        
        if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            echo "✅ ZIP file created\n";
            
            // Add a test file
            $test_content = "This is a test file content";
            $zip->addFromString('test.txt', $test_content);
            echo "✅ File added to ZIP\n";
            
            $zip->close();
            echo "✅ ZIP file closed\n";
            
            if (file_exists($zip_file)) {
                echo "✅ ZIP file exists\n";
                unlink($zip_file); // Clean up
            }
        } else {
            echo "❌ Failed to create ZIP file\n";
        }
    } else {
        echo "❌ ZipArchive class not available\n";
    }
    
    // Test PHPMailer
    if ($vendor_loaded && class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "✅ PHPMailer class available\n";
        
        // Test creating PHPMailer instance
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        echo "✅ PHPMailer instance created\n";
        
        // Test basic configuration
        $mail->isSMTP();
        echo "✅ SMTP configured\n";
        
    } else {
        echo "❌ PHPMailer class not available\n";
    }
    
    echo "\n=== PDF PROCESSING TEST COMPLETED ===\n";
    
    echo json_encode([
        'success' => true,
        'message' => 'PDF processing test completed successfully',
        'vendor_loaded' => $vendor_loaded
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Exception: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Error $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Fatal Error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>

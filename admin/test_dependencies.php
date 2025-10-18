<?php
/**
 * Test dependencies and file structure
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Dependency and File Structure Test</h2>";

// Test 1: Check if vendor3/autoload.php exists
echo "<h3>1. Checking vendor3/autoload.php</h3>";
if (file_exists('vendor3/autoload.php')) {
    echo "✅ vendor3/autoload.php exists<br>";
    try {
        require 'vendor3/autoload.php';
        echo "✅ vendor3/autoload.php loaded successfully<br>";
    } catch (Exception $e) {
        echo "❌ Error loading vendor3/autoload.php: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ vendor3/autoload.php NOT FOUND<br>";
    echo "Current directory: " . getcwd() . "<br>";
    echo "Script directory: " . __DIR__ . "<br>";
    echo "Files in current directory:<br>";
    $files = scandir('.');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file<br>";
        }
    }
}

// Test 2: Check FPDI class
echo "<h3>2. Checking FPDI class</h3>";
if (class_exists('setasign\Fpdi\Fpdi')) {
    echo "✅ FPDI class available<br>";
} else {
    echo "❌ FPDI class not available<br>";
}

// Test 3: Check PHPMailer classes
echo "<h3>3. Checking PHPMailer classes</h3>";
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✅ PHPMailer class available<br>";
} else {
    echo "❌ PHPMailer class not available<br>";
}

// Test 4: Check ZipArchive
echo "<h3>4. Checking ZipArchive</h3>";
if (class_exists('ZipArchive')) {
    echo "✅ ZipArchive class available<br>";
} else {
    echo "❌ ZipArchive class not available<br>";
}

// Test 5: Check DEASemester directory
echo "<h3>5. Checking DEASemester directory</h3>";
if (is_dir('DEASemester')) {
    echo "✅ DEASemester directory exists<br>";
    $pdf_files = glob('DEASemester/*.pdf');
    echo "PDF files found: " . count($pdf_files) . "<br>";
    foreach ($pdf_files as $file) {
        echo "- " . basename($file) . "<br>";
    }
} else {
    echo "❌ DEASemester directory NOT FOUND<br>";
}

// Test 6: Check temp directory creation
echo "<h3>6. Testing directory creation</h3>";
$test_dir = "temp/test_" . time();
if (mkdir($test_dir, 0777, true)) {
    echo "✅ Directory creation works<br>";
    if (rmdir($test_dir)) {
        echo "✅ Directory deletion works<br>";
    } else {
        echo "❌ Directory deletion failed<br>";
    }
} else {
    echo "❌ Directory creation failed<br>";
}

// Test 7: Check deacompress directory
echo "<h3>7. Checking deacompress directory</h3>";
if (is_dir('deacompress')) {
    echo "✅ deacompress directory exists<br>";
} else {
    echo "❌ deacompress directory NOT FOUND<br>";
    if (mkdir('deacompress', 0777, true)) {
        echo "✅ Created deacompress directory<br>";
    } else {
        echo "❌ Failed to create deacompress directory<br>";
    }
}

echo "<h3>Test Complete</h3>";
?>

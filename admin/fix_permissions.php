<?php
/**
 * Fix file permissions for temp directories
 */
echo "<h2>Fixing File Permissions</h2>";

$directories = [
    'temp',
    'deacompress', 
    'logs',
    'DEASemester'
];

foreach ($directories as $dir) {
    echo "<p>Processing directory: $dir</p>";
    
    if (!is_dir($dir)) {
        if (mkdir($dir, 0777, true)) {
            echo "✅ Created directory: $dir<br>";
        } else {
            echo "❌ Failed to create directory: $dir<br>";
        }
    } else {
        echo "✅ Directory exists: $dir<br>";
    }
    
    if (chmod($dir, 0777)) {
        echo "✅ Set permissions 777 for: $dir<br>";
    } else {
        echo "❌ Failed to set permissions for: $dir<br>";
    }
    
    // Test write permission
    $test_file = $dir . '/test_write_' . time() . '.txt';
    if (file_put_contents($test_file, 'test') !== false) {
        echo "✅ Write test successful for: $dir<br>";
        unlink($test_file); // Clean up
    } else {
        echo "❌ Write test failed for: $dir<br>";
    }
    
    echo "<br>";
}

echo "<h3>Current directory permissions:</h3>";
echo "<pre>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $perms = fileperms($file);
        $perms_str = substr(sprintf('%o', $perms), -4);
        echo "$file: $perms_str\n";
    }
}
echo "</pre>";

echo "<h3>Test PDF creation:</h3>";
if (file_exists('vendor3/autoload.php')) {
    require 'vendor3/autoload.php';
    
    try {
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Permission Test PDF', 0, 1, 'C');
        
        $test_pdf = 'temp/permission_test_' . time() . '.pdf';
        $pdf->Output($test_pdf, 'F');
        
        if (file_exists($test_pdf)) {
            echo "✅ PDF creation successful: $test_pdf<br>";
            unlink($test_pdf); // Clean up
        } else {
            echo "❌ PDF creation failed<br>";
        }
    } catch (Exception $e) {
        echo "❌ PDF creation error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ vendor3/autoload.php not found<br>";
}

echo "<h3>Permission fix completed!</h3>";
?>

<?php
/**
 * Fix Docker container permissions
 */
echo "<h2>Docker Permission Fix</h2>";

// Check current user
echo "<p><strong>Current user:</strong> " . exec('whoami') . "</p>";
echo "<p><strong>Current group:</strong> " . exec('groups') . "</p>";
echo "<p><strong>PHP user:</strong> " . exec('php -r "echo get_current_user();"') . "</p>";

// Try to fix permissions using shell commands
echo "<h3>Attempting to fix permissions...</h3>";

$commands = [
    'chmod -R 777 temp',
    'chmod -R 777 deacompress', 
    'chmod -R 777 logs',
    'chmod -R 777 DEASemester',
    'chown -R www-data:www-data temp',
    'chown -R www-data:www-data deacompress',
    'chown -R www-data:www-data logs',
    'chown -R www-data:www-data DEASemester'
];

foreach ($commands as $cmd) {
    echo "<p>Running: $cmd</p>";
    $output = shell_exec($cmd . ' 2>&1');
    if ($output) {
        echo "<pre>$output</pre>";
    } else {
        echo "✅ Command executed<br>";
    }
}

// Test write permissions again
echo "<h3>Testing write permissions after fix...</h3>";

$test_dirs = ['temp', 'deacompress', 'logs', 'DEASemester'];

foreach ($test_dirs as $dir) {
    if (is_dir($dir)) {
        $test_file = $dir . '/test_write_' . time() . '.txt';
        if (file_put_contents($test_file, 'test') !== false) {
            echo "✅ Write test successful for: $dir<br>";
            unlink($test_file); // Clean up
        } else {
            echo "❌ Write test failed for: $dir<br>";
        }
    }
}

// Test PDF creation
echo "<h3>Testing PDF creation...</h3>";
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
}

echo "<h3>Permission fix completed!</h3>";
?>

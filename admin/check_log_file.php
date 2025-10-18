<?php
/**
 * Check the log file from the test
 */
$log_file = 'email_test_2025-10-18_21-27-20.log';
$log_path = __DIR__ . '/' . $log_file;

echo "<h2>Log File Contents</h2>";
echo "<p><strong>File:</strong> $log_path</p>";

if (file_exists($log_path)) {
    echo "<h3>File exists! Contents:</h3>";
    echo "<pre>";
    echo htmlspecialchars(file_get_contents($log_path));
    echo "</pre>";
} else {
    echo "<h3>File not found!</h3>";
    echo "<p>Checking directory contents:</p>";
    $files = scandir(__DIR__);
    echo "<pre>";
    foreach ($files as $file) {
        if (strpos($file, 'email_test_') === 0) {
            echo "Found log file: $file\n";
            echo "Contents:\n";
            echo htmlspecialchars(file_get_contents(__DIR__ . '/' . $file));
            echo "\n---\n";
        }
    }
    echo "</pre>";
}
?>

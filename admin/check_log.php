<?php
// Check the latest log file
echo "<h2>Checking Latest Log File</h2>";

// Find the latest log file
$log_dir = __DIR__;
$log_files = glob($log_dir . '/email_simplified_*.log');

if (empty($log_files)) {
    echo "No log files found<br>";
    exit;
}

// Sort by modification time (newest first)
usort($log_files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

$latest_log = $log_files[0];
echo "Latest log file: " . basename($latest_log) . "<br>";

if (file_exists($latest_log)) {
    echo "<h3>Log Contents:</h3>";
    echo "<pre>" . htmlspecialchars(file_get_contents($latest_log)) . "</pre>";
} else {
    echo "Log file not found<br>";
}
?>

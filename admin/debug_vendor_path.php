<?php
// Debug vendor3 path and autoload
echo "<h2>Debugging Vendor3 Path</h2>";

echo "<h3>1. Current Directory</h3>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Current working directory: " . getcwd() . "<br>";

echo "<h3>2. Vendor3 Path Check</h3>";
$vendor_paths = [
    'vendor3/autoload.php',
    __DIR__ . '/../vendor3/autoload.php',
    '/var/www/html/vendor3/autoload.php',
    './vendor3/autoload.php',
    '../vendor3/autoload.php'
];

foreach ($vendor_paths as $path) {
    if (file_exists($path)) {
        echo "✅ EXISTS: $path<br>";
    } else {
        echo "❌ NOT FOUND: $path<br>";
    }
}

echo "<h3>3. Directory Listing</h3>";
echo "Root directory contents:<br>";
$root_files = scandir(__DIR__ . '/..');
foreach ($root_files as $file) {
    if ($file != '.' && $file != '..') {
        $full_path = __DIR__ . '/../' . $file;
        $type = is_dir($full_path) ? '[DIR]' : '[FILE]';
        echo "$type $file<br>";
    }
}

echo "<h3>4. Vendor3 Directory Contents</h3>";
$vendor_dir = __DIR__ . '/../vendor3';
if (is_dir($vendor_dir)) {
    echo "Vendor3 directory exists. Contents:<br>";
    $vendor_files = scandir($vendor_dir);
    foreach ($vendor_files as $file) {
        if ($file != '.' && $file != '..') {
            $full_path = $vendor_dir . '/' . $file;
            $type = is_dir($full_path) ? '[DIR]' : '[FILE]';
            echo "$type $file<br>";
        }
    }
} else {
    echo "❌ Vendor3 directory does not exist<br>";
}

echo "<h3>5. Autoload File Check</h3>";
$autoload_path = __DIR__ . '/../vendor3/autoload.php';
if (file_exists($autoload_path)) {
    echo "✅ Autoload file exists<br>";
    echo "File size: " . filesize($autoload_path) . " bytes<br>";
    echo "First 200 characters:<br>";
    echo "<pre>" . htmlspecialchars(substr(file_get_contents($autoload_path), 0, 200)) . "</pre>";
} else {
    echo "❌ Autoload file does not exist<br>";
}

echo "<h3>6. Composer Check</h3>";
$composer_json = __DIR__ . '/../composer.json';
if (file_exists($composer_json)) {
    echo "✅ composer.json exists<br>";
    echo "Content:<br>";
    echo "<pre>" . htmlspecialchars(file_get_contents($composer_json)) . "</pre>";
} else {
    echo "❌ composer.json does not exist<br>";
}
?>

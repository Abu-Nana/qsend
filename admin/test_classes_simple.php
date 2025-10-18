<?php
// Simple test script to check if classes are available (no authentication required)
echo "<h2>Testing Classes Availability</h2>";

echo "<h3>1. Loading Autoload</h3>";
$autoload_path = __DIR__ . '/../vendor3/autoload.php';
if (file_exists($autoload_path)) {
    require $autoload_path;
    echo "✅ Autoload loaded successfully<br>";
} else {
    echo "❌ Autoload file not found<br>";
    exit;
}

echo "<h3>2. Checking Class Availability</h3>";
$classes_to_check = [
    'PHPMailer\\PHPMailer\\PHPMailer',
    'PHPMailer\\PHPMailer\\SMTP',
    'PHPMailer\\PHPMailer\\Exception',
    'setasign\\Fpdi\\Fpdi',
    'setasign\\Fpdf\\Fpdf'
];

foreach ($classes_to_check as $class) {
    if (class_exists($class)) {
        echo "✅ Class exists: $class<br>";
    } else {
        echo "❌ Class not found: $class<br>";
    }
}

echo "<h3>3. Testing Instantiation</h3>";
try {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    echo "✅ PHPMailer instantiated successfully<br>";
} catch (Exception $e) {
    echo "❌ PHPMailer failed: " . $e->getMessage() . "<br>";
}

try {
    $pdf = new \setasign\Fpdi\Fpdi();
    echo "✅ FPDI instantiated successfully<br>";
} catch (Exception $e) {
    echo "❌ FPDI failed: " . $e->getMessage() . "<br>";
}

try {
    $fpdf = new \setasign\Fpdf\Fpdf();
    echo "✅ FPDF instantiated successfully<br>";
} catch (Exception $e) {
    echo "❌ FPDF failed: " . $e->getMessage() . "<br>";
}

echo "<h3>4. FPDF Alternative Test</h3>";
// Try to use the old FPDF class that might be available
if (class_exists('FPDF')) {
    echo "✅ Old FPDF class exists<br>";
    try {
        $old_fpdf = new FPDF();
        echo "✅ Old FPDF instantiated successfully<br>";
    } catch (Exception $e) {
        echo "❌ Old FPDF failed: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Old FPDF class not found<br>";
}

echo "<h3>5. Composer Info</h3>";
echo "Composer autoloader loaded: " . (class_exists('Composer\\Autoload\\ClassLoader') ? 'Yes' : 'No') . "<br>";
echo "Registered autoload functions: " . count(spl_autoload_functions()) . "<br>";
?>

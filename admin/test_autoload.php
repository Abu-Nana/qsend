<?php
// Test autoload functionality
echo "<h2>Testing Autoload Functionality</h2>";

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

echo "<h3>3. Testing PHPMailer Instantiation</h3>";
try {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    echo "✅ PHPMailer instantiated successfully<br>";
} catch (Exception $e) {
    echo "❌ PHPMailer instantiation failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ PHPMailer instantiation failed with fatal error: " . $e->getMessage() . "<br>";
}

echo "<h3>4. Testing FPDI Instantiation</h3>";
try {
    $pdf = new \setasign\Fpdi\Fpdi();
    echo "✅ FPDI instantiated successfully<br>";
} catch (Exception $e) {
    echo "❌ FPDI instantiation failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ FPDI instantiation failed with fatal error: " . $e->getMessage() . "<br>";
}

echo "<h3>5. Testing FPDF Instantiation</h3>";
try {
    $fpdf = new \setasign\Fpdf\Fpdf();
    echo "✅ FPDF instantiated successfully<br>";
} catch (Exception $e) {
    echo "❌ FPDF instantiation failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ FPDF instantiation failed with fatal error: " . $e->getMessage() . "<br>";
}

echo "<h3>6. Checking Autoload Functions</h3>";
$autoload_functions = spl_autoload_functions();
echo "Registered autoload functions: " . count($autoload_functions) . "<br>";
foreach ($autoload_functions as $i => $function) {
    if (is_array($function)) {
        echo "Autoload $i: " . (is_object($function[0]) ? get_class($function[0]) : $function[0]) . "::" . $function[1] . "<br>";
    } else {
        echo "Autoload $i: $function<br>";
    }
}

echo "<h3>7. Testing Manual Class Loading</h3>";
// Try to manually include PHPMailer files
$phpmailer_paths = [
    __DIR__ . '/../vendor3/phpmailer/phpmailer/src/PHPMailer.php',
    __DIR__ . '/../vendor3/phpmailer/phpmailer/src/SMTP.php',
    __DIR__ . '/../vendor3/phpmailer/phpmailer/src/Exception.php'
];

foreach ($phpmailer_paths as $path) {
    if (file_exists($path)) {
        echo "✅ PHPMailer file exists: " . basename($path) . "<br>";
    } else {
        echo "❌ PHPMailer file not found: " . basename($path) . "<br>";
    }
}
?>

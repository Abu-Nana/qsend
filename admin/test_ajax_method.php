<?php
// Test different AJAX request methods
session_start();

// Set session variables
$_SESSION['admin'] = 'test001';
$_SESSION['username'] = 'testadmin';
$_SESSION['name'] = 'Test Administrator';
$_SESSION['u_cat'] = 'dea';
$_SESSION['role'] = 'System Administrator';

echo "<h2>Testing Different AJAX Methods</h2>";

// Test 1: GET request
echo "<h3>1. Testing GET Request</h3>";
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET['exam_type'] = 'normal';
$_GET['exam_session'] = '8:30am';
$_GET['exam_day'] = 'Day 1';
$_GET['question_folder'] = 'Testing on Cloud 8am';
$_GET['subject'] = 'Testing on Cloud 8am';
$_GET['body'] = '';

try {
    ob_start();
    include 'qsend_ajax_original.php';
    $output = ob_get_clean();
    echo "✅ GET request successful<br>";
    echo "Output: " . htmlspecialchars(substr($output, 0, 200)) . "...<br>";
} catch (Exception $e) {
    echo "❌ GET request failed: " . $e->getMessage() . "<br>";
}

// Test 2: POST request with form data
echo "<h3>2. Testing POST Request</h3>";
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['exam_type'] = 'normal';
$_POST['exam_session'] = '8:30am';
$_POST['exam_day'] = 'Day 1';
$_POST['question_folder'] = 'Testing on Cloud 8am';
$_POST['subject'] = 'Testing on Cloud 8am';
$_POST['body'] = '';

try {
    ob_start();
    include 'qsend_ajax_original.php';
    $output = ob_get_clean();
    echo "✅ POST request successful<br>";
    echo "Output: " . htmlspecialchars(substr($output, 0, 200)) . "...<br>";
} catch (Exception $e) {
    echo "❌ POST request failed: " . $e->getMessage() . "<br>";
}

// Test 3: Check if the script expects specific content type
echo "<h3>3. Content Type Test</h3>";
echo "Current CONTENT_TYPE: " . ($_SERVER['CONTENT_TYPE'] ?? 'Not set') . "<br>";
echo "Current REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "<br>";

// Test 4: Check if the script has any early exits
echo "<h3>4. Early Exit Check</h3>";
$script_content = file_get_contents('qsend_ajax_original.php');
$exit_positions = [];
$pos = 0;
while (($pos = strpos($script_content, 'exit', $pos)) !== false) {
    $line_number = substr_count(substr($script_content, 0, $pos), "\n") + 1;
    $exit_positions[] = $line_number;
    $pos++;
}

echo "Found " . count($exit_positions) . " exit statements at lines: " . implode(', ', $exit_positions) . "<br>";

// Test 5: Check if the script has any die statements
$die_positions = [];
$pos = 0;
while (($pos = strpos($script_content, 'die', $pos)) !== false) {
    $line_number = substr_count(substr($script_content, 0, $pos), "\n") + 1;
    $die_positions[] = $line_number;
    $pos++;
}

echo "Found " . count($die_positions) . " die statements at lines: " . implode(', ', $die_positions) . "<br>";
?>

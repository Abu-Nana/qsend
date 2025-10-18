<?php
// Test AJAX with proper POST data simulation
session_start();

// Set session variables
$_SESSION['admin'] = 'test001';
$_SESSION['username'] = 'testadmin';
$_SESSION['name'] = 'Test Administrator';
$_SESSION['u_cat'] = 'dea';
$_SESSION['role'] = 'System Administrator';

echo "<h2>Testing AJAX with Proper POST Data</h2>";

// Test 1: Simulate the exact POST data from the browser
echo "<h3>1. Simulating Browser POST Data</h3>";

// Clear any existing POST data
$_POST = array();

// Set the exact POST data that the browser sends
$_POST['exam_type'] = 'normal';
$_POST['exam_session'] = '8:30am';
$_POST['exam_day'] = 'Day 1';
$_POST['question_folder'] = 'Testing on Cloud 8am';
$_POST['subject'] = 'Testing on Cloud 8am';
$_POST['body'] = '';

// Set server variables
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';

echo "POST data set:<br>";
foreach ($_POST as $key => $value) {
    echo "- $key: $value<br>";
}

// Test 2: Try to include the main script
echo "<h3>2. Including Main Script</h3>";
try {
    ob_start();
    include 'qsend_ajax_original.php';
    $output = ob_get_clean();
    echo "✅ Script executed successfully<br>";
    echo "Output: " . htmlspecialchars($output) . "<br>";
} catch (Exception $e) {
    echo "❌ Script failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ Script failed with fatal error: " . $e->getMessage() . "<br>";
}

// Test 3: Check what the script is actually receiving
echo "<h3>3. Debugging POST Data Reception</h3>";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'Not set') . "<br>";
echo "CONTENT_TYPE: " . ($_SERVER['CONTENT_TYPE'] ?? 'Not set') . "<br>";
echo "POST data count: " . count($_POST) . "<br>";

// Test 4: Check if the script is looking for specific field names
echo "<h3>4. Field Name Check</h3>";
$required_fields = ['exam_type', 'exam_session', 'exam_day', 'subject'];
foreach ($required_fields as $field) {
    if (isset($_POST[$field])) {
        echo "✅ Field '$field' is set: " . $_POST[$field] . "<br>";
    } else {
        echo "❌ Field '$field' is missing<br>";
    }
}

// Test 5: Check if there are any validation issues
echo "<h3>5. Validation Check</h3>";
if (empty($_POST['exam_type'])) {
    echo "❌ exam_type is empty<br>";
} else {
    echo "✅ exam_type has value: " . $_POST['exam_type'] . "<br>";
}

if (empty($_POST['exam_session'])) {
    echo "❌ exam_session is empty<br>";
} else {
    echo "✅ exam_session has value: " . $_POST['exam_session'] . "<br>";
}

if (empty($_POST['exam_day'])) {
    echo "❌ exam_day is empty<br>";
} else {
    echo "✅ exam_day has value: " . $_POST['exam_day'] . "<br>";
}

if (empty($_POST['subject'])) {
    echo "❌ subject is empty<br>";
} else {
    echo "✅ subject has value: " . $_POST['subject'] . "<br>";
}
?>

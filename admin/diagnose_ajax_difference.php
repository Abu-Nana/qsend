<?php
// Diagnose the difference between working test and failing AJAX
session_start();

// Set session variables like the real AJAX call would have
$_SESSION['admin'] = 'test001';
$_SESSION['username'] = 'testadmin';
$_SESSION['name'] = 'Test Administrator';
$_SESSION['u_cat'] = 'dea';
$_SESSION['role'] = 'System Administrator';

echo "<h2>Diagnosing AJAX Difference</h2>";

// Test 1: Check if the main script file exists and is readable
echo "<h3>1. File Existence Check</h3>";
$script_path = __DIR__ . '/qsend_ajax_original.php';
if (file_exists($script_path)) {
    echo "✅ Main script exists: $script_path<br>";
    if (is_readable($script_path)) {
        echo "✅ Main script is readable<br>";
    } else {
        echo "❌ Main script is NOT readable<br>";
    }
} else {
    echo "❌ Main script does NOT exist: $script_path<br>";
}

// Test 2: Check if we can include the script without errors
echo "<h3>2. Script Include Test</h3>";
try {
    ob_start();
    include $script_path;
    $output = ob_get_clean();
    echo "✅ Script included successfully<br>";
    echo "Output length: " . strlen($output) . " characters<br>";
} catch (Exception $e) {
    echo "❌ Script include failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ Script include failed with fatal error: " . $e->getMessage() . "<br>";
}

// Test 3: Check if the script handles POST data properly
echo "<h3>3. POST Data Simulation</h3>";
$_POST['exam_type'] = 'normal';
$_POST['exam_session'] = '8:30am';
$_POST['exam_day'] = 'Day 1';
$_POST['question_folder'] = 'Testing on Cloud 8am';
$_POST['subject'] = 'Testing on Cloud 8am';
$_POST['body'] = '';

echo "POST data set:<br>";
foreach ($_POST as $key => $value) {
    echo "- $key: $value<br>";
}

// Test 4: Try to simulate the exact AJAX call
echo "<h3>4. AJAX Simulation Test</h3>";
try {
    ob_start();
    
    // Simulate the exact request
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
    
    // Include the script
    include $script_path;
    
    $ajax_output = ob_get_clean();
    echo "✅ AJAX simulation completed<br>";
    echo "Output: " . htmlspecialchars(substr($ajax_output, 0, 200)) . "...<br>";
    
} catch (Exception $e) {
    echo "❌ AJAX simulation failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ AJAX simulation failed with fatal error: " . $e->getMessage() . "<br>";
}

// Test 5: Check for any output buffering issues
echo "<h3>5. Output Buffering Check</h3>";
$ob_level = ob_get_level();
echo "Current output buffering level: $ob_level<br>";

// Test 6: Check if there are any fatal errors in the script
echo "<h3>6. Script Syntax Check</h3>";
$syntax_check = shell_exec("php -l $script_path 2>&1");
echo "Syntax check result:<br>";
echo "<pre>" . htmlspecialchars($syntax_check) . "</pre>";

echo "<h3>7. Session Information</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data:<br>";
foreach ($_SESSION as $key => $value) {
    echo "- $key: $value<br>";
}
?>

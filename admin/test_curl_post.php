<?php
// Test making an actual cURL POST request to the AJAX endpoint
session_start();

// Set session variables
$_SESSION['admin'] = 'test001';
$_SESSION['username'] = 'testadmin';
$_SESSION['name'] = 'Test Administrator';
$_SESSION['u_cat'] = 'dea';
$_SESSION['role'] = 'System Administrator';

echo "<h2>Testing cURL POST Request</h2>";

// Test 1: Make a cURL request to the AJAX endpoint
echo "<h3>1. Making cURL POST Request</h3>";

$post_data = array(
    'exam_type' => 'normal',
    'exam_session' => '8:30am',
    'exam_day' => 'Day 1',
    'question_folder' => 'Testing on Cloud 8am',
    'subject' => 'Testing on Cloud 8am',
    'body' => ''
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://qsend.nou.edu.ng/admin/qsend_ajax_original.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=' . session_id());

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);

curl_close($ch);

echo "HTTP Code: $http_code<br>";
echo "Headers:<br>";
echo "<pre>" . htmlspecialchars($headers) . "</pre>";
echo "Response Body:<br>";
echo "<pre>" . htmlspecialchars($body) . "</pre>";

// Test 2: Check if the session is working
echo "<h3>2. Session Check</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data:<br>";
foreach ($_SESSION as $key => $value) {
    echo "- $key: $value<br>";
}

// Test 3: Try a local file include test
echo "<h3>3. Local File Include Test</h3>";
try {
    // Set POST data for local test
    $_POST = $post_data;
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
    
    ob_start();
    include 'qsend_ajax_original.php';
    $local_output = ob_get_clean();
    
    echo "✅ Local include successful<br>";
    echo "Output: " . htmlspecialchars($local_output) . "<br>";
} catch (Exception $e) {
    echo "❌ Local include failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ Local include failed with fatal error: " . $e->getMessage() . "<br>";
}
?>

<?php
/**
 * Basic Mail AJAX Handler - No SMTP, No Attachments
 * Uses basic mail function that's already working
 */

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering to catch any unexpected output
ob_start();

// Start session
session_start();

// Include database connection
include 'includes/conn.php';

// Check if user is logged in
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

// Get user details
$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->bind_param("s", $_SESSION['admin']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    exit;
}

// Set header for JSON response
header('Content-Type: application/json');

// Prevent timeout for long processes
set_time_limit(300); // 5 minutes
ini_set('max_execution_time', 300);

try {
    // Get POST data
    $exam_session = isset($_POST['exam_session']) ? $_POST['exam_session'] : '';
    $day = isset($_POST['exam_day']) ? $_POST['exam_day'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';

    // Validate required fields
    if (empty($exam_session) || empty($day) || empty($subject)) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields'
        ]);
        exit;
    }

    // Get database connection
    $connection = require 'connection.php';
    $files_table = "files";

    // Query to get study centers and their courses
    $script = "SELECT s.id, s.matric_number, s.study_center, s.study_center_code, s.course, s.exam_day, s.exam_session, s.exa_date, c.study_center_code, c.study_centre_email, c.phone_number, c.director 
                FROM student_registrations s
                INNER JOIN study_centers c 
                ON c.study_center_code = s.study_center_code 
                WHERE s.exam_session = ? 
                AND s.exam_day = ?
                GROUP BY c.study_center_code, s.course";

    $stmt = mysqli_prepare($connection, $script);

    if (!$stmt) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database query preparation failed: ' . mysqli_error($connection)
        ]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "ss", $exam_session, $day);
    mysqli_stmt_execute($stmt);
    $query_data = mysqli_stmt_get_result($stmt);

    if (!$query_data) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database query failed: ' . mysqli_error($connection)
        ]);
        exit;
    }

    // Organize data by center
    $centers = [];
    if (mysqli_num_rows($query_data) > 0) {
        while ($obj = mysqli_fetch_assoc($query_data)) {
            $centers[$obj['study_center_code']][] = $obj;
        }
    } else {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'No data found for the specified exam session and day'
        ]);
        exit;
    }

    // Initialize response data
    $recipients = [];
    $sent_count = 0;
    $failed_count = 0;
    $total_centers = count($centers);

    // User information
    $username = $user['username'];
    $firstName = $user['firstname'];
    $lastName = $user['lastname'];
    $fullName = $username . ' - ' . $firstName . ' ' . $lastName;
    $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown IP';

    // Process each study center
    foreach ($centers as $center => $value) {
        $recipient_data = [
            'study_center' => '',
            'center_code' => $center,
            'email' => '',
            'director' => '',
            'password' => '',
            'status' => 'failed',
            'error_message' => ''
        ];

        try {
            // Get center information
            $exam_session_val = isset($value[0]['exam_session']) ? $value[0]['exam_session'] : '_session';
            $study_center = isset($value[0]['study_center']) ? $value[0]['study_center'] : '_session';
            $exam_day = isset($value[0]['exam_day']) ? $value[0]['exam_day'] : '_session';
            $director = isset($value[0]['director']) ? $value[0]['director'] : 'Unknown';
            $email = isset($value[0]['study_centre_email']) ? $value[0]['study_centre_email'] : '';

            $recipient_data['study_center'] = $study_center;
            $recipient_data['director'] = $director;
            $recipient_data['email'] = $email;

            // Generate password
            $password = "dea" . rand(1000, 999999);
            $recipient_data['password'] = $password;

            // Create file name for database record
            $file_name = str_replace(':', '_', str_replace(' ', '_', $study_center . $center . "_" . $exam_session_val . '_' . $exam_day));
            $file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_name);
            $zipfile = "deacompress/" . $file_name . ".zip";

            // Store in database (without creating actual ZIP for now)
            $store = "INSERT INTO $files_table 
                (study_center, student_center_name, exam_session, exam_day, password, file_name, sem, sentby, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?, '251', ?, ?)";
            
            $stmt_store = mysqli_prepare($connection, $store);
            mysqli_stmt_bind_param($stmt_store, "ssssssss", $study_center, $center, $exam_session, $exam_day, $password, $zipfile, $fullName, $ipAddress);
            mysqli_stmt_execute($stmt_store);
            mysqli_stmt_close($stmt_store);

            // Send email using basic mail function
            if (!empty($email)) {
                $email_result = sendBasicEmail($email, $password, $subject, $body, $study_center);
                
                if ($email_result['success']) {
                    $recipient_data['status'] = 'sent';
                    $sent_count++;
                } else {
                    $recipient_data['status'] = 'failed';
                    $recipient_data['error_message'] = $email_result['error'];
                    $failed_count++;
                }
            } else {
                $recipient_data['status'] = 'failed';
                $recipient_data['error_message'] = 'No email address provided';
                $failed_count++;
            }

        } catch (Exception $e) {
            $recipient_data['status'] = 'failed';
            $recipient_data['error_message'] = $e->getMessage();
            $failed_count++;
            error_log("Error processing center $center: " . $e->getMessage());
        }

        $recipients[] = $recipient_data;
    }

    // Return success response
    $response = [
        'success' => true,
        'message' => 'Questions processed successfully',
        'exam_session' => $exam_session,
        'exam_day' => $day,
        'total_centers' => $total_centers,
        'sent_count' => $sent_count,
        'failed_count' => $failed_count,
        'sent_by' => $fullName,
        'recipients' => $recipients
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
    error_log("QSEND Error: " . $e->getMessage());
}

// Clear any unexpected output
ob_clean();

// Ensure we only output JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;

/**
 * Send basic email using PHP mail function
 */
function sendBasicEmail($email, $password, $subject, $body, $study_center) {
    try {
        // Create email message
        $message = "
POP Examination Questions

" . (!empty($body) ? $body . "\n\n" : "") . "
Password to open the file: $password

IMPORTANT INSTRUCTIONS:
- Please use WinRAR application to extract the zipped file
- Download WinRAR from: https://www.win-rar.com
- Right-click on the downloaded file
- Select 'Extract to (folder name)' from the list
- Input the password shown above and click OK
- Open the extracted folder to access your questions

Please do not reply to this email.
For any requests or inquiries, please send an email to dea@noun.edu.ng

---
Directorate of Examinations & Assessment
        ";
        
        // Set email headers
        $headers = "From: deatech@noun.edu.ng\r\n";
        $headers .= "Reply-To: deatech@noun.edu.ng\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: QSEND System\r\n";
        
        // Send email
        if (mail($email, $subject, $message, $headers)) {
            return ['success' => true, 'error' => ''];
        } else {
            return ['success' => false, 'error' => 'Mail function returned false'];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>


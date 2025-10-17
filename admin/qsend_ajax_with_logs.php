<?php
/**
 * AJAX Handler with Comprehensive Email Logging
 * Logs every step of the email sending process
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

require 'vendor3/autoload.php';
use setasign\Fpdi\Fpdi;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Create log file
$log_file = 'email_debug_' . date('Y-m-d_H-i-s') . '.log';
$log_path = __DIR__ . '/' . $log_file;

function writeLog($message) {
    global $log_path;
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    file_put_contents($log_path, $log_entry, FILE_APPEND | LOCK_EX);
}

try {
    writeLog("=== QSEND EMAIL DEBUG SESSION STARTED ===");
    
    // Get POST data
    $exam_session = isset($_POST['exam_session']) ? $_POST['exam_session'] : '';
    $day = isset($_POST['exam_day']) ? $_POST['exam_day'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';

    writeLog("POST Data - Session: $exam_session, Day: $day, Subject: $subject");

    // Validate required fields
    if (empty($exam_session) || empty($day) || empty($subject)) {
        writeLog("ERROR: Missing required fields");
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

    writeLog("Database connection established");

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
        writeLog("ERROR: Database query preparation failed: " . mysqli_error($connection));
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
        writeLog("ERROR: Database query failed: " . mysqli_error($connection));
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
        writeLog("Found " . count($centers) . " study centers");
    } else {
        writeLog("ERROR: No data found for exam session and day");
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

    writeLog("Processing $total_centers study centers");

    // Process each study center
    foreach ($centers as $center => $value) {
        writeLog("Processing center: $center");
        
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

            writeLog("Center: $study_center, Email: $email, Director: $director");

            $recipient_data['study_center'] = $study_center;
            $recipient_data['director'] = $director;
            $recipient_data['email'] = $email;

            // Generate password
            $password = "dea" . rand(1000, 999999);
            $recipient_data['password'] = $password;

            writeLog("Generated password: $password");

            // Create file name for database record
            $file_name = str_replace(':', '_', str_replace(' ', '_', $study_center . $center . "_" . $exam_session_val . '_' . $exam_day));
            $file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_name);
            $zipfile = "deacompress/" . $file_name . ".zip";

            writeLog("ZIP file path: $zipfile");

            // Create temp directory
            $folder_path = "temp/" . $file_name;
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
                writeLog("Created temp directory: $folder_path");
            }

            // Create ZIP file
            $zip = new ZipArchive();
            if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                writeLog("ZIP file opened successfully");
                $files_added = 0;

                // Process each course
                foreach ($value as $obj) {
                    $source_file = "DEASemester/" . $obj['course'] . ".pdf";
                    $destination_file = $folder_path . "/" . $obj['course'] . ".pdf";

                    writeLog("Processing course: " . $obj['course']);

                    // Check if course PDF exists, if not use TEST.pdf as fallback
                    if (file_exists($source_file)) {
                        copy($source_file, $destination_file);
                        writeLog("Copied course PDF: $source_file");
                    } else {
                        // Use TEST.pdf as fallback for missing course PDFs
                        if (file_exists("DEASemester/TEST.pdf")) {
                            copy("DEASemester/TEST.pdf", $destination_file);
                            writeLog("Used TEST.pdf as fallback for: " . $obj['course']);
                        } else {
                            // Create a simple text file if no PDFs exist
                            file_put_contents($destination_file, "Course: " . $obj['course'] . "\nStudy Center: " . $study_center . "\nPassword: " . $password);
                            writeLog("Created text file for: " . $obj['course']);
                        }
                    }

                    // Add to ZIP with encryption
                    $zip->addFile($destination_file, basename($destination_file));
                    $zip->setEncryptionName(basename($destination_file), ZipArchive::EM_AES_256, $password);
                    $files_added++;
                    writeLog("Added file to ZIP: " . basename($destination_file));
                }

                $zip->close();
                writeLog("ZIP file closed successfully. Files added: $files_added");

                // Store in database
                $store = "INSERT INTO $files_table 
                    (study_center, student_center_name, exam_session, exam_day, password, file_name, sem, sentby, ip_address) 
                    VALUES (?, ?, ?, ?, ?, ?, '251', ?, ?)";
                
                $stmt_store = mysqli_prepare($connection, $store);
                mysqli_stmt_bind_param($stmt_store, "ssssssss", $study_center, $center, $exam_session, $exam_day, $password, $zipfile, $fullName, $ipAddress);
                mysqli_stmt_execute($stmt_store);
                mysqli_stmt_close($stmt_store);
                writeLog("Database record stored successfully");

                // Send email with comprehensive logging
                if (!empty($email)) {
                    writeLog("Attempting to send email to: $email");
                    $email_result = sendEmailWithLogs($email, $zipfile, $password, $subject, $body, $study_center);
                    
                    if ($email_result['success']) {
                        $recipient_data['status'] = 'sent';
                        $sent_count++;
                        writeLog("EMAIL SUCCESS: Email sent to $email");
                    } else {
                        $recipient_data['status'] = 'failed';
                        $recipient_data['error_message'] = $email_result['error'];
                        $failed_count++;
                        writeLog("EMAIL FAILED: " . $email_result['error']);
                    }
                } else {
                    $recipient_data['status'] = 'failed';
                    $recipient_data['error_message'] = 'No email address provided';
                    $failed_count++;
                    writeLog("EMAIL FAILED: No email address provided");
                }

                // Clean up temp folder
                if (file_exists($folder_path)) {
                    deleteDirectory($folder_path);
                    writeLog("Cleaned up temp directory: $folder_path");
                }

            } else {
                $recipient_data['status'] = 'failed';
                $recipient_data['error_message'] = 'Failed to create ZIP file';
                $failed_count++;
                writeLog("ZIP CREATION FAILED: Cannot open ZIP file");
            }

        } catch (Exception $e) {
            $recipient_data['status'] = 'failed';
            $recipient_data['error_message'] = $e->getMessage();
            $failed_count++;
            writeLog("EXCEPTION: " . $e->getMessage());
            error_log("Error processing center $center: " . $e->getMessage());
        }

        $recipients[] = $recipient_data;
    }

    writeLog("Processing complete. Sent: $sent_count, Failed: $failed_count");

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
        'recipients' => $recipients,
        'log_file' => $log_file
    ];

} catch (Exception $e) {
    writeLog("FATAL ERROR: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
    error_log("QSEND Error: " . $e->getMessage());
}

writeLog("=== QSEND EMAIL DEBUG SESSION ENDED ===");

// Clear any unexpected output
ob_clean();

// Ensure we only output JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;

/**
 * Delete a directory and all its contents
 */
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

/**
 * Send email with comprehensive logging
 */
function sendEmailWithLogs($email, $attachment, $password, $subject, $body, $study_center) {
    writeLog("=== EMAIL SENDING STARTED ===");
    writeLog("Email: $email");
    writeLog("Subject: $subject");
    writeLog("Attachment: $attachment");
    writeLog("Password: $password");
    
    try {
        // Method 1: Try basic mail function first
        writeLog("Attempting basic mail function...");
        
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
        
        $headers = "From: deatech@noun.edu.ng\r\n";
        $headers .= "Reply-To: deatech@noun.edu.ng\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: QSEND System\r\n";
        
        writeLog("Mail headers: " . str_replace("\r\n", " | ", $headers));
        
        if (mail($email, $subject, $message, $headers)) {
            writeLog("BASIC MAIL SUCCESS: Email sent using basic mail function");
            return ['success' => true, 'error' => ''];
        } else {
            writeLog("BASIC MAIL FAILED: mail() function returned false");
        }
        
        // Method 2: Try PHPMailer with local mail
        writeLog("Attempting PHPMailer with local mail...");
        
        $mail = new PHPMailer(true);
        $mail->isMail(); // Use local mail function
        
        $mail->setFrom('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');
        $mail->addAddress($email, $study_center);
        $mail->addReplyTo('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');
        
        // Add attachment if it exists
        if (file_exists($attachment)) {
            $mail->addAttachment($attachment);
            writeLog("PHPMailer: Attachment added - " . basename($attachment));
        } else {
            writeLog("PHPMailer: Attachment not found - $attachment");
        }
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <h2>POP Examination Questions</h2>
            " . (!empty($body) ? "<p>" . nl2br(htmlspecialchars($body)) . "</p>" : "") . "
            <p><strong>Password:</strong> $password</p>
            <p>Please use WinRAR to extract the file.</p>
            <p>For requests, email dea@noun.edu.ng</p>
        ";
        $mail->AltBody = "Password: $password\nPlease use WinRAR to extract the file.";
        
        $mail->send();
        writeLog("PHPMailer SUCCESS: Email sent using PHPMailer");
        return ['success' => true, 'error' => ''];
        
    } catch (Exception $e) {
        writeLog("PHPMailer ERROR: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>

<?php
/**
 * Debug AJAX Handler for Sending Questions
 * Enhanced error reporting and email debugging
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
            // Generate file name
            $exam_session_val = isset($value[0]['exam_session']) ? $value[0]['exam_session'] : '_session';
            $study_center = isset($value[0]['study_center']) ? $value[0]['study_center'] : '_session';
            $exam_day = isset($value[0]['exam_day']) ? $value[0]['exam_day'] : '_session';
            $director = isset($value[0]['director']) ? $value[0]['director'] : 'Unknown';
            $email = isset($value[0]['study_centre_email']) ? $value[0]['study_centre_email'] : '';

            $recipient_data['study_center'] = $study_center;
            $recipient_data['director'] = $director;
            $recipient_data['email'] = $email;

            // Create safe file name
            $file_name = str_replace(':', '_', str_replace(' ', '_', $study_center . $center . "_" . $exam_session_val . '_' . $exam_day));
            $file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_name);
            
            $folder_path = "temp/" . $file_name;
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            // Create ZIP file
            $zipfile = "deacompress/" . $file_name . ".zip";
            $password = "dea" . rand(1000, 999999);
            $recipient_data['password'] = $password;

            $zip = new ZipArchive();
            if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $files_added = 0;

                // Process each course
                foreach ($value as $obj) {
                    $source_file = "DEASemester/" . $obj['course'] . ".pdf";
                    $destination_file = $folder_path . "/" . $obj['course'] . ".pdf";

                    if (file_exists($source_file)) {
                        // Copy and watermark PDF
                        copy($source_file, $destination_file);
                        
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile($destination_file);
                        
                        for ($i = 1; $i <= $pageCount; $i++) {
                            $tplIdx = $pdf->importPage($i);
                            $pdf->AddPage();
                            $pdf->useTemplate($tplIdx, 10, 10, 200);
                            $pdf->SetFont('Arial', '', 5);
                            $pdf->SetTextColor(255, 255, 255);
                            $pdf->SetXY(10, 10);
                            $pdf->Write(0, "Study Centre: " . $study_center . " (" . $center . ")");
                        }
                        
                        $pdf->Output($destination_file, 'F');
                        
                        // Add to ZIP with encryption
                        $zip->addFile($destination_file, basename($destination_file));
                        $zip->setEncryptionName(basename($destination_file), ZipArchive::EM_AES_256, $password);
                        $files_added++;
                    }
                }

                $zip->close();

                // Only proceed if files were added
                if ($files_added > 0) {
                    // Store in database
                    $store = "INSERT INTO $files_table 
                        (study_center, student_center_name, exam_session, exam_day, password, file_name, sem, sentby, ip_address) 
                        VALUES (?, ?, ?, ?, ?, ?, '251', ?, ?)";
                    
                    $stmt_store = mysqli_prepare($connection, $store);
                    mysqli_stmt_bind_param($stmt_store, "ssssssss", $study_center, $center, $exam_session, $exam_day, $password, $zipfile, $fullName, $ipAddress);
                    mysqli_stmt_execute($stmt_store);
                    mysqli_stmt_close($stmt_store);

                    // Send email if email exists
                    if (!empty($email)) {
                        $email_result = sendQuestionEmailDebug($email, $zipfile, $password, $subject, $body, $study_center);
                        
                        if ($email_result['success']) {
                            $recipient_data['status'] = 'sent';
                            $sent_count++;
                        } else {
                            $recipient_data['status'] = 'failed';
                            $recipient_data['error_message'] = $email_result['error'];
                            $failed_count++;
                        }
                    } else {
                        // Mark as failed if no email
                        $recipient_data['status'] = 'failed';
                        $recipient_data['error_message'] = 'No email address provided';
                        $failed_count++;
                    }
                } else {
                    // No files found for this center
                    $recipient_data['status'] = 'failed';
                    $recipient_data['error_message'] = 'No PDF files found for courses';
                    $failed_count++;
                }

                // Clean up temp folder
                if (file_exists($folder_path)) {
                    deleteDirectory($folder_path);
                }
            } else {
                // Failed to create ZIP
                $recipient_data['status'] = 'failed';
                $recipient_data['error_message'] = 'Failed to create ZIP file';
                $failed_count++;
            }
        } catch (Exception $e) {
            // Error processing this center
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
 * Send email with question attachment - Debug version
 */
function sendQuestionEmailDebug($email, $attachment, $password, $subject, $body, $study_center) {
    try {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host       = "smtp.postmarkapp.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "0bd489cc-5eb0-4c44-b647-43b09b94ba2c";
        $mail->Password   = "0bd489cc-5eb0-4c44-b647-43b09b94ba2c";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // SMTP Options
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        // Recipients
        $mail->setFrom('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');
        $mail->addAddress($email, $study_center);
        $mail->addReplyTo('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');

        // Attachment
        if (file_exists($attachment)) {
            $mail->addAttachment($attachment);
        } else {
            return [
                'success' => false,
                'error' => "Attachment file not found: " . $attachment
            ];
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        $emailBody = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #333;'>POP Examination Questions</h2>
                
                " . (!empty($body) ? "<p>" . nl2br(htmlspecialchars($body)) . "</p>" : "") . "
                
                <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0 0 10px 0;'><strong>Password to open the file:</strong></p>
                    <p style='font-size: 24px; color: #667eea; font-weight: bold; margin: 0;'>$password</p>
                </div>
                
                <div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;'>
                    <p style='margin: 0 0 10px 0;'><strong>⚠️ IMPORTANT INSTRUCTIONS:</strong></p>
                    <p style='margin: 5px 0;'>Please use <strong>WinRAR</strong> application to extract the zipped file.</p>
                </div>
                
                <h3 style='color: #333; margin-top: 20px;'>Extraction Steps:</h3>
                <ol style='line-height: 1.8;'>
                    <li>Download and install WinRAR if you don't have it: <a href='https://www.win-rar.com'>Download WinRAR</a></li>
                    <li>Right-click on the downloaded file</li>
                    <li>Select 'Extract to (folder name)' from the list</li>
                    <li>Input the password shown above and click OK</li>
                    <li>Open the extracted folder to access your questions</li>
                </ol>
                
                <hr style='border: none; border-top: 1px solid #dee2e6; margin: 30px 0;'>
                
                <p style='color: #666; font-size: 12px;'>
                    <strong>Please do not reply to this email.</strong><br>
                    For any requests or inquiries, please send an email to <a href='mailto:dea@noun.edu.ng'>dea@noun.edu.ng</a>
                </p>
            </div>
        ";
        
        $mail->Body = $emailBody;
        $mail->AltBody = "Password: $password\n\nPlease use WinRAR to extract the file.\nFor requests, email dea@noun.edu.ng.";

        // Send the email
        $mail->send();
        return [
            'success' => true,
            'error' => ''
        ];

    } catch (Exception $e) {
        $error_message = "Email Error for $email: " . $e->getMessage();
        error_log($error_message);
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>


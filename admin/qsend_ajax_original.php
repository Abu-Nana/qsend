<?php
/**
 * AJAX Handler - Using Original Working Email System
 * Integrates with pdf_with_security.php and mailerbatch1.php
 */

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering to catch any unexpected output
ob_start();

// Start session
session_start();

// Include database connection
require_once __DIR__ . '/../config/database.php';
$conn = Database::getPDO();

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
$stmt->execute([$_SESSION['admin']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

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
$log_file = 'email_original_' . date('Y-m-d_H-i-s') . '.log';
$log_path = __DIR__ . '/' . $log_file;

function writeLog($message) {
    global $log_path;
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    file_put_contents($log_path, $log_entry, FILE_APPEND | LOCK_EX);
}

try {
    writeLog("=== ORIGINAL EMAIL SYSTEM SESSION STARTED ===");
    
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

    // Use existing database connection
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

    $stmt = $conn->prepare($script);

    if (!$stmt) {
        writeLog("ERROR: Database query preparation failed");
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database query preparation failed'
        ]);
        exit;
    }

    $stmt->execute([$exam_session, $day]);
    $query_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$query_data) {
        writeLog("ERROR: Database query failed");
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database query failed'
        ]);
        exit;
    }

    // Organize data by center
    $centers = [];
    if (count($query_data) > 0) {
        foreach ($query_data as $obj) {
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
            $password = "dea" . rand(1, 1000000);
            $recipient_data['password'] = $password;

            writeLog("Generated password: $password");

            // Create file name
            $file_name = str_replace(':', '_', str_replace(' ', '_', $study_center . $center . "_" . $exam_session_val . '_' . $exam_day));
            $file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_name);
            $zipfile = "deacompress/" . $file_name . ".zip";
            $folder_path = "temp/" . $file_name;

            writeLog("ZIP file path: $zipfile");

            // Create temp directory
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
                writeLog("Created temp directory: $folder_path");
            }

            // Create ZIP file with watermarked PDFs
            $zip = new ZipArchive();
            if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                writeLog("ZIP file opened successfully");
                $status = false;

                // Process each course
                foreach ($value as $obj) {
                    $source_file = "DEASemester/" . $obj['course'] . ".pdf";
                    $destination_file = $folder_path . "/" . $obj['course'] . ".pdf";

                    writeLog("Processing course: " . $obj['course']);

                    if (file_exists($source_file)) {
                        copy($source_file, $destination_file);
                        
                        // Add watermark using FPDI
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile($destination_file);
                        for ($i = 1; $i <= $pageCount; $i++) {
                            $tplIdx = $pdf->importPage($i);
                            $pdf->AddPage();
                            $pdf->useTemplate($tplIdx, 10, 10, 200);
                            $pdf->SetFont('Arial', '', 5);
                            $pdf->SetTextColor(255, 255, 255); // White text
                            $pdf->SetXY(10, 10);
                            $pdf->Write(0, "Study Centre: " . $study_center . " (" . $center . ")");
                        }
                        $pdf->Output($destination_file, 'F');
                        
                        // Add to ZIP with encryption
                        $zip->addFile($destination_file, basename($destination_file));
                        $zip->setEncryptionName(basename($destination_file), ZipArchive::EM_AES_256, $password);
                        $status = true;
                        writeLog("Added watermarked PDF to ZIP: " . $obj['course']);
                    } else {
                        writeLog("WARNING: Course PDF not found: $source_file");
                    }
                }

                $zip->close();
                writeLog("ZIP file closed successfully");

                // Store in database
                $store = "INSERT INTO $files_table 
                    (study_center, student_center_name, exam_session, exam_day, password, file_name, sem, sentby, ip_address) 
                    VALUES (?, ?, ?, ?, ?, ?, '251', ?, ?)";
                
                $stmt_store = mysqli_prepare($connection, $store);
                mysqli_stmt_bind_param($stmt_store, "ssssssss", $study_center, $center, $exam_session, $exam_day, $password, $zipfile, $fullName, $ipAddress);
                mysqli_stmt_execute($stmt_store);
                mysqli_stmt_close($stmt_store);
                writeLog("Database record stored successfully");

                // Clean up temp folder
                if (file_exists($folder_path)) {
                    deleteDirectory($folder_path);
                    writeLog("Cleaned up temp directory: $folder_path");
                }

                // Send email using original mailerbatch1.php method
                if (!empty($email)) {
                    writeLog("Attempting to send email using original method to: $email");
                    $email_result = sendEmailOriginal($email, $zipfile, $password, $subject, $body, $study_center, $exam_session, $day);
                    
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
}

writeLog("=== ORIGINAL EMAIL SYSTEM SESSION ENDED ===");

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
 * Send email using the original mailerbatch1.php method
 */
function sendEmailOriginal($email, $attachment, $password, $subject, $body, $study_center, $exam_session, $day) {
    writeLog("=== USING ORIGINAL MAILERBATCH1.PHP METHOD ===");
    writeLog("Email: $email");
    writeLog("Attachment: $attachment");
    writeLog("Password: $password");
    
    try {
        // Use the exact same PHPMailer setup as mailerbatch1.php
        $mail = new PHPMailer(true);
        
        // Server settings (from mailerbatch1.php)
        $mail->isSMTP();
        $mail->Host       = "smtp.postmarkapp.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "0bd489cc-5eb0-4c44-b647-43b09b94ba2c";
        $mail->Password   = "0bd489cc-5eb0-4c44-b647-43b09b94ba2c";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // SMTP Options (from mailerbatch1.php)
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        
        writeLog("SMTP configured: smtp.postmarkapp.com:587");
        
        // Recipients (from mailerbatch1.php)
        $mail->setFrom('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');
        $mail->addAddress($email, $center);
        $mail->addReplyTo('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');
        
        writeLog("Recipients set");
        
        // Attachments (from mailerbatch1.php)
        if (file_exists($attachment)) {
            $mail->addAttachment($attachment);
            writeLog("Attachment added: " . basename($attachment));
        } else {
            throw new Exception("Attachment file not found: $attachment");
        }
        
        // Content - Modern Email Template (Generic Study Centre Version)
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Examination Questions</title>
</head>
<body style='margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif; background-color: #f5f7fa;'>
    <table cellpadding='0' cellspacing='0' border='0' width='100%' style='background-color: #f5f7fa; padding: 40px 0;'>
        <tr>
            <td align='center'>
                <table cellpadding='0' cellspacing='0' border='0' width='600' style='background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); overflow: hidden;'>
                    
                    <!-- Header -->
                    <tr>
                        <td style='background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 40px 30px; text-align: center;'>
                            <h1 style='margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;'>
                                &#128218; Examination Questions
                            </h1>
                            <p style='margin: 10px 0 0; color: #e0e7ff; font-size: 14px; font-weight: 500;'>
                                Directorate of Examinations & Assessment
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style='padding: 40px 30px;'>
                            
                            <!-- Greeting -->
                            <p style='margin: 0 0 20px; color: #1f2937; font-size: 16px; line-height: 1.6;'>
                                Dear $study_center,
                            </p>
                            
                            <p style='margin: 0 0 30px; color: #4b5563; font-size: 15px; line-height: 1.6;'>
                                Please find attached the examination questions for <strong>Day $day</strong> of <strong>$exam_session</strong> session. Use the password below to access the encrypted files.
                            </p>
                            
                            <!-- Password Box -->
                            <table cellpadding='0' cellspacing='0' border='0' width='100%' style='background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 10px; border: 2px solid #10b981; margin: 0 0 30px;'>
                                <tr>
                                    <td style='padding: 25px 20px; text-align: center;'>
                                        <p style='margin: 0 0 8px; color: #065f46; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;'>
                                            &#128273; Your Access Password
                                        </p>
                                        <p style='margin: 0; color: #047857; font-size: 32px; font-weight: 700; font-family: \"Courier New\", monospace; letter-spacing: 2px;'>
                                            $password
                                        </p>
                                        <p style='margin: 8px 0 0; color: #059669; font-size: 12px; font-style: italic;'>
                                            Keep this password secure and confidential
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Instructions -->
                            <table cellpadding='0' cellspacing='0' border='0' width='100%' style='background-color: #fef3c7; border-radius: 10px; border-left: 4px solid #f59e0b; margin: 0 0 30px;'>
                                <tr>
                                    <td style='padding: 20px;'>
                                        <h2 style='margin: 0 0 15px; color: #92400e; font-size: 18px; font-weight: 700;'>
                                            &#128203; Extraction Instructions
                                        </h2>
                                        <p style='margin: 0 0 15px; color: #78350f; font-size: 14px; line-height: 1.6;'>
                                            Please use <strong>WinRAR</strong> to extract the attached files. Follow these simple steps:
                                        </p>
                                        
                                        <table cellpadding='0' cellspacing='0' border='0' width='100%'>
                                            <tr>
                                                <td style='padding: 0 0 12px;'>
                                                    <table cellpadding='0' cellspacing='0' border='0'>
                                                        <tr>
                                                            <td style='width: 28px; vertical-align: top;'>
                                                                <div style='background-color: #f59e0b; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;'>1</div>
                                                            </td>
                                                            <td style='padding-left: 12px; color: #78350f; font-size: 14px; line-height: 1.5;'>
                                                                Download and install <strong>WinRAR</strong> from <a href='https://www.win-rar.com' style='color: #ea580c; text-decoration: none; font-weight: 600;'>www.win-rar.com</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 0 0 12px;'>
                                                    <table cellpadding='0' cellspacing='0' border='0'>
                                                        <tr>
                                                            <td style='width: 28px; vertical-align: top;'>
                                                                <div style='background-color: #f59e0b; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;'>2</div>
                                                            </td>
                                                            <td style='padding-left: 12px; color: #78350f; font-size: 14px; line-height: 1.5;'>
                                                                Right-click on the downloaded ZIP file
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 0 0 12px;'>
                                                    <table cellpadding='0' cellspacing='0' border='0'>
                                                        <tr>
                                                            <td style='width: 28px; vertical-align: top;'>
                                                                <div style='background-color: #f59e0b; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;'>3</div>
                                                            </td>
                                                            <td style='padding-left: 12px; color: #78350f; font-size: 14px; line-height: 1.5;'>
                                                                Select <strong>Extract Here</strong> or <strong>Extract to folder</strong>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 0 0 12px;'>
                                                    <table cellpadding='0' cellspacing='0' border='0'>
                                                        <tr>
                                                            <td style='width: 28px; vertical-align: top;'>
                                                                <div style='background-color: #f59e0b; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;'>4</div>
                                                            </td>
                                                            <td style='padding-left: 12px; color: #78350f; font-size: 14px; line-height: 1.5;'>
                                                                Enter the password shown above when prompted
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table cellpadding='0' cellspacing='0' border='0'>
                                                        <tr>
                                                            <td style='width: 28px; vertical-align: top;'>
                                                                <div style='background-color: #f59e0b; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;'>5</div>
                                                            </td>
                                                            <td style='padding-left: 12px; color: #78350f; font-size: 14px; line-height: 1.5;'>
                                                                Open the extracted folder to access your questions
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Important Notice -->
                            <table cellpadding='0' cellspacing='0' border='0' width='100%' style='background-color: #fee2e2; border-radius: 10px; border-left: 4px solid #ef4444; margin: 0 0 30px;'>
                                <tr>
                                    <td style='padding: 20px;'>
                                        <h3 style='margin: 0 0 10px; color: #991b1b; font-size: 16px; font-weight: 700;'>
                                            &#9888; Important Notice
                                        </h3>
                                        <ul style='margin: 0; padding: 0 0 0 20px; color: #7f1d1d; font-size: 14px; line-height: 1.8;'>
                                            <li style='margin: 0 0 8px;'>Keep the password <strong>confidential</strong></li>
                                            <li style='margin: 0 0 8px;'>Ensure files are handled <strong>securely</strong></li>
                                            <li style='margin: 0 0 8px;'>Do not share examination materials</li>
                                            <li style='margin: 0;'>Contact us immediately if you encounter any issues</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support -->
                            <table cellpadding='0' cellspacing='0' border='0' width='100%' style='background-color: #eff6ff; border-radius: 10px; padding: 20px;'>
                                <tr>
                                    <td>
                                        <h3 style='margin: 0 0 10px; color: #1e40af; font-size: 16px; font-weight: 700;'>
                                            &#128172; Need Help?
                                        </h3>
                                        <p style='margin: 0; color: #1e3a8a; font-size: 14px; line-height: 1.6;'>
                                            For any questions, technical support, or concerns, please contact us at:
                                        </p>
                                        <p style='margin: 10px 0 0;'>
                                            <a href='mailto:dea@noun.edu.ng' style='display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; text-align: center;'>
                                                &#9993; dea@noun.edu.ng
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style='background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;'>
                            <p style='margin: 0 0 8px; color: #6b7280; font-size: 13px; line-height: 1.5;'>
                                <strong>Directorate of Examinations & Assessment</strong><br>
                                National Open University of Nigeria
                            </p>
                            
                            <p style='margin: 15px 0 0; color: #d1d5db; font-size: 11px;'>
                                © " . date('Y') . " National Open University of Nigeria. All rights reserved.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
        ";
        
        // Plain text alternative
        $mail->AltBody = "
EXAMINATION QUESTIONS
Directorate of Examinations & Assessment

Dear $study_center,

Please find attached the examination questions for Day $day of $exam_session session.

YOUR ACCESS PASSWORD: $password

Keep this password secure and confidential.

EXTRACTION INSTRUCTIONS:
1. Download and install WinRAR from www.win-rar.com
2. Right-click on the downloaded ZIP file
3. Select 'Extract Here' or 'Extract to folder'
4. Enter the password shown above when prompted
5. Open the extracted folder to access your questions

IMPORTANT NOTICE:
- Keep the password confidential
- Ensure files are handled securely
- Do not share examination materials
- Contact us immediately if you encounter any issues

NEED HELP?
For any questions, technical support, or concerns, please contact us at:
examinations@yourinstitution.edu

---
Directorate of Examinations & Assessment
National Open University of Nigeria
© " . date('Y') . " National Open University of Nigeria. All rights reserved.
        ";
        
        writeLog("Email content prepared");
        
        // Send the email
        writeLog("Attempting to send email...");
        $mail->send();
        
        writeLog("ORIGINAL METHOD SUCCESS: Email sent successfully");
        return ['success' => true, 'error' => ''];
        
    } catch (Exception $e) {
        $error = "Mailer Error: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage();
        writeLog("ORIGINAL METHOD ERROR: " . $error);
        return ['success' => false, 'error' => $error];
    }
}
?>

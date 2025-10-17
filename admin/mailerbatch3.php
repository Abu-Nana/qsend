<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once "vendor3/autoload.php";

$mail = new PHPMailer(true);

try {
    // Enable debugging for troubleshooting
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = 'html';

    // Server settings
    $mail->isSMTP();
    $mail->Host       = "email-smtp.eu-central-1.amazonaws.com"; // Replace with your AWS SES region endpoint
    $mail->SMTPAuth   = true;
    $mail->Username   = "AKIASIVGLOW226235IEE"; // Replace with your AWS SES SMTP username
    $mail->Password   = "BGhbsMU18s4OMUho6avUkEOzj2LlFPXETfZoRsIVszxK"; // Replace with your AWS SES SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
    $mail->Port       = 587; // AWS SES supports ports 25, 465, or 587 (use 587 for TLS)

    // Validate input variables
    if (empty($email) || empty($subject) || empty($password)) {
        throw new Exception("One or more required variables (email, subject, password) are not set.");
    }
    if (!file_exists($attachment)) {
        throw new Exception("Attachment not found at path: " . $attachment);
    }

    // Recipients
    $mail->setFrom('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment'); // Update as needed
    $mail->addAddress($email, 'Directorate of Examinations & Assessment'); // Add recipient
    $mail->addReplyTo('dea@noun.edu.ng', 'Directorate of Examinations & Assessment');

    // Attachments
    $mail->addAttachment($attachment);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = "
        <p>Kindly find below the password to open the attached file for this session:</p>
        <p><b>Password:</b> $password</p>
        <p>Please do not reply to this email. For any request, kindly send an email to <a href='mailto:dea@noun.edu.ng'>dea@noun.edu.ng</a>.</p>
    ";
    $mail->AltBody = "Password: $password\nPlease do not reply to this email. For requests, email dea@noun.edu.ng.";

    // Send the email
    if ($mail->send()) {
        echo "Email sent successfully.";
    } else {
        throw new Exception("Email sending failed: " . $mail->ErrorInfo);
    }
} catch (Exception $e) {
    error_log("Mailer Error: " . $e->getMessage());
    error_log("SMTP Error Info: " . $mail->ErrorInfo);
    echo "Email sending failed. Check error log for details.";
}

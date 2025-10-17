<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once "vendor3/autoload.php";
// Include the file that establishes a database connection
$connection = require 'connection.php';

// PHPMailer Object
$mail = new PHPMailer(true); // Argument true in the constructor enables exceptions

try {
    // Set up email server settings
    $mail->isSMTP(); // Send using SMTP
    $mail->Host = "smtp.gmail.com"; // Set the SMTP server to send through
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable implicit TLS encryption
    $mail->Port = 587; // Enable SMTP authentication
    $mail->Username = "ondemand@noun.edu.ng";
    $mail->Password = "dea@noun2022$";

    // Query to retrieve data based on the selected day and session, joining files and study_centers
    $session = isset($_POST['session']) ? $_POST['session'] : null;
    $day = isset($_POST['day']) ? $_POST['day'] : null;

    if (!$session || !$day) {
        throw new Exception("Please select session and day.");
    }

    $query = "SELECT f.*, sc.study_centre_email 
              FROM files f 
              JOIN study_centers sc ON f.study_center = sc.study_center_code 
              WHERE f.exam_session = '$session' AND f.exam_day = '$day'";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        // Loop through each record
        while ($row = $result->fetch_assoc()) {
            $email = $row['study_centre_email']; // Get the email of the study center
            $exam_session = $row['exam_session']; // Get the exam session
            $day = $row['exam_day']; // Get the exam day

            // Create and send email
            $mail->setFrom("ondemand@noun.edu.ng", 'Directorate of Examinations & Assessment');
            $mail->addAddress($email, 'Directorate of Examinations & Assessment');
            $mail->addReplyTo("ondemand@noun.edu.ng", 'Directorate of Examinations & Assessment');

            // Attach the zip file
            $attachment = $row['file_name'];
            $mail->addAttachment($attachment);

            // Retrieve the email subject from the form
            $subject = isset($_POST['subject']) ? $_POST['subject'] : 'Exam Questions'; // Default subject if not provided

            // Email content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject . " Batch " . $exam_session . " " . $day; // Update the subject with the retrieved value from the form
            $mail->Body = 'Kindly find the attached zip file with password. Thank you. <br>';
            $mail->AltBody = 'Auto Generated Email';

            // Send email
            if (!$mail->send()) {
                throw new Exception("Message could not be sent. Mailer Error: " . $mail->ErrorInfo);
            }
        }
    } else {
        throw new Exception("No data found in files table for the selected session and day.");
    }
} catch (Exception $e) {
    echo $e->getMessage() . "<br>";
} finally {
    $connection->close(); // Close the database connection
}
?>

<?php
/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once "vendor3/autoload.php";
//PHPMailer Object

$mail = new PHPMailer(true); //Argument true in constructor enables exceptions

//From email address and name

try {       
    //Server settings
   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
   $mail->Host = "c76069.sgvps.net";                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;           
    $mail->SMTPSecure = 'tls';          //Enable implicit TLS encryption
   // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 465;                          //Enable SMTP authentication
   $mail->Username = "exams@dea.nou.edu.ng"; // SMTP username
$mail->Password = "#>)#Qtj@321@";
    // $mail->SMTPOptions = ['ssl'=> ['allow_self_signed' => true]];                              //SMTP 
   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('exams@dea.nou.edu.ng', 'Directorate of Examinations & Assessment');
    $mail->addAddress($email, 'Directorate of Examinations & Assessment');     //Add a recipient
    $mail->addReplyTo($email, 'Directorate of Examinations & Assessment');
    $mail->addAttachment($attachment);
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
   $mail->Body    ='Kindly find below password to open the file for this session. <b> Please do not reply to this email. For any request, kindly send an email to dea@noun.edu.ng <br> Password : '.$password;
    $mail->AltBody = 'Auto Generated Email';
    $mail->send();
    //          echo "Questions sent to: " .$counter. " - ".$study_center." : Centre Director:  ".$director. "Email: " . $email . "<br>";

} catch (Exception $e) {
   echo "Email sending failed: " . $mailer->ErrorInfo . "<br>";
}
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once "vendor3/autoload.php";

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = "smtp.postmarkapp.com"; // Postmark's SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = "0bd489cc-5eb0-4c44-b647-43b09b94ba2c"; // Your Postmark API key as the username
    $mail->Password   = "0bd489cc-5eb0-4c44-b647-43b09b94ba2c"; // Same Postmark API key as the password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587; // Typically 587 for TLS

    // SMTP Options (if needed)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    // Recipients
   // $mail->setFrom('exams@dea.nou.edu.ng', 'Directorate of Examinations & Assessment');
   // $mail->addAddress($email, 'Recipient Name');
   // $mail->addReplyTo('dea@noun.edu.ng', 'No-Reply');

    //Recipients
    $mail->setFrom('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');
    $mail->addAddress($email, 'Directorate of Examinations & Assessment');     //Add a recipient
    $mail->addReplyTo('deatech@noun.edu.ng', 'Directorate of Examinations & Assessment');
    $mail->addAttachment($attachment);

    // Attachments
    if (file_exists($attachment)) {
        $mail->addAttachment($attachment);
    } else {
        throw new Exception("Attachment file not found.");
    }

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = "

        <p>Kindly find below the password to open the attached file for this session:</p>
        <p><b>Password:</b> $password</p>
        <p><b>Please do not reply to this email.</b> For any request, kindly send an email to <a href='mailto:dea@noun.edu.ng'>dea@noun.edu.ng</a>.</p>
        <p><b>NOTE!</b></p>
        <p>Please use <b>WinRAR</b> application to always extract the zipped file.</p>
        <p>Steps:</p>
        <p>1- Download and install WinRAR on your computer if you don't have it installed</p>
        <p>2- Get WinRAR at <a href='https://www.win-rar.com'>Download WinRAR</a></p>
        <p>3- Right-click on the downloaded file</p>
        <p>4- Select Extract to (StudyCentreNameCodeSession) from the list</p>
        <p>5- Input your password for the session and click OK</p>
        <p>6- Open the extracted folder to get your questions</p>
    ";
    $mail->AltBody = "Password: $password\nPlease do not reply to this email. For requests, email dea@noun.edu.ng.";

    // Send the email
    $mail->send();
    echo "Email sent successfully.";
} catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    echo "Email sending failed. Please check the configuration or server settings.";
}
?>


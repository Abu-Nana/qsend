<?php
use PHPMailer\PHPMailer\PHPMailer;
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
    $mail->Host = "mail.dea.nou.edu.ng";                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;           
    $mail->SMTPSecure = 'tls';          //Enable implicit TLS encryption
    $mail->Port       = 587;                          //Enable SMTP authentication
   $mail->Username = "exams@dea.nou.edu.ng";
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
    $mail->Body    ='Kindly find below password to open the file for this session. <b> Please do not reply to this email. . For any request, kindly send an email to dea@noun.edu.ng <br> Password : '.$password;
    $mail->AltBody = 'Auto Generated Email';
    $mail->send();
          echo "Questions sent to: ".$study_center." : Centre Director:  ".$director. "Email: " . $email . "<br>";

} catch (Exception $e) {
   echo "Email sending failed: " . $mailer->ErrorInfo . "<br>";
}
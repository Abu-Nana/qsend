<?php
    require 'vendor/autoload.php';
    use Dompdf\Dompdf;
use Dompdf\FontMetrics;
include 'dbconfig.php';

    $email = 'balhassan@noun.edu.ng';
    $query = mysqli_query($dbconnection,"select *from payslip where `email` = '$email'");
    $row = $query->fetch_assoc();
//    echo json_encode($row);
//exit(0);
    //Inisialisasi object dompdf dan twig
    $dompdf = new Dompdf();
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    //Encoding file logo dari png ke base64 karena dompdf tidak suport gambar, hanya support text
    $path = 'templates/logo.png';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    //Merencer Template kosong (invoice.html) menggunakan twig dan menghasilkan kode html
    $html = $twig->render('invoice1.html', array(
        'row' => $row,
    ));
    
    //Proses Merender kode HTML kedalam bentuk PDF
    $dompdf->loadHtml($html);
    $dompdf->set_option('isRemoteEnabled', true);
    $dompdf->render();
// Instantiate canvas instance
$canvas = $dompdf->getCanvas();

// Get height and width of page
$w = $canvas->get_width();
$h = $canvas->get_height();

// Specify watermark image
$imageURL = 'https://i.postimg.cc/DS1ggx2t/logo.png';
$imgWidth = 400;
$imgHeight = 400;

// Set image opacity
$canvas->set_opacity(.3);

// Specify horizontal and vertical position
$x = (($w-$imgWidth)/2);
$y = (($h-$imgHeight)/2);

// Add an image to the pdf
$canvas->image($imageURL, $x, $y, $imgWidth, $imgHeight);
//$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
$file = $dompdf->output();
$file_name = rand(000001,999999).'.pdf';
file_put_contents($file_name, $file);
file_put_contents($file_name, $file);

require "phpmailer/PHPMailerAutoload.php";
$mail = new PHPMailer;
$mail->Host       = 'localhost';                    // Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'payrol@noun.edu.ng';                     // SMTP username
$mail->Password   = 'payrol@2022';                               // SMTP password
$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
$mail->Port       = 465;
$mail->From = 'payrol@noun.edu.ng';   //Sets the From email address for the message
$mail->FromName = 'HAK';   //Sets the From name of the message
//$mail->AddAddress($user_email, $user_name);  //Adds a "To" address
$mail->WordWrap = 50;       //Sets word wrapping on the body of the message to a given number of characters
$mail->IsHTML(true);       //Sets message type to HTML
$mail->Subject = 'Customer Invoice';   //Sets the Subject of the message
$mail->Body = '';
if($mail->Send())        //Send an Email. Return true on success or false on error
{
    $message = '<label class="text-success">Customer Details has been send successfully...</label>';
}
else{
    echo $mail->ErrorInfo;
}
unlink($file_name);
exit(0);

?>
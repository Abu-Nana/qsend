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
$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));

exit(0);

?>
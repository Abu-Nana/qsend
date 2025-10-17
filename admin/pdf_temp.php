<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
include 'dbconfig.php';
$output = '
<html>
<body>
</body>
</html>
';
?>

<?php
$pdf = new Dompdf();
$file_name = 'ANAGarmentsInvoice-.pdf';
$pdf->loadHtml($output);
$pdf->render();
?>
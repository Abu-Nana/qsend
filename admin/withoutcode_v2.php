<?php
$connection = require 'connection.php';
require 'vendor3/autoload.php';
use AfricasTalking\SDK\AfricasTalking;
use setasign\Fpdi\Fpdi;

try{
	$exam_session = $_POST['session'];
	$day = $_POST['day'];
	$subject = $_POST['subject'];
	$body = $_POST['body'];

	if(!isset($exam_session)){
		echo "please select session exam";
		return false;
	}

	$date = "Dated ".date('y-m-d');
	$script = "SELECT student_registrations_stage.*,study_centers_stage2.study_center_code, study_centers_stage2.study_centre_email,study_centers_stage2.phone_number FROM `student_registrations_stage` INNER JOIN `study_centers_stage2` ON `study_centers_stage2`.`study_center_code` = `student_registrations_stage`.`study_center_code` WHERE `exam_session` = '".$exam_session ."' AND `exam_day` = '".$day ."' GROUP BY `study_centers_stage2`.`study_center_code`, `student_registrations_stage`.`course'";
	$query_data = mysqli_query($connection, $script);
	$zip = new \ZipArchive();
	$centers = [];

	if(mysqli_num_rows($query_data) > 0)  {
		while($obj = mysqli_fetch_assoc($query_data)){
			$centers[$obj['study_center_code']][] = $obj;
		}
	}

	foreach ($centers as $center => $value) {
		$exam_session = isset($centers[$center][0]['exam_session']) ? $centers[$center][0]['exam_session'] : '_session';
		$study_center = isset($centers[$center][0]['study_center']) ? $centers[$center][0]['study_center'] : '_session';
		$exam_day = isset($centers[$center][0]['exam_day']) ? $centers[$center][0]['exam_day'] : '_session';
		$file_name = str_replace(':','_',str_replace(' ','_',$study_center.$center."_".$exam_session. '_'. $exam_day));
		$zipfile = "stage_compress/".$file_name.".zip";
		$password = "dea".rand(1,1000000);
		$status = false;

		if($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE){
			$zip->setPassword($password);

			foreach ($value as  $obj) {
				$dir = "stage_pdf/".$obj['course'].".pdf";
				if(file_exists($dir)){
					$code = ":".rand(1,100000000);
					securityCode($dir, $obj, $code, $connection); // put security
					$zip->addFile($dir);
					$zip->setEncryptionName($dir, ZipArchive::EM_AES_256);
					$status = true;
				}
			}

			$zip->close();

			if($status == true){
				$email = isset($centers[$center][0]['study_centre_email']) ? $centers[$center][0]['study_centre_email'] : '';
				$phone_number = isset($centers[$center][0]['phone_number']) ? $centers[$center][0]['phone_number'] : '';
				sendEmail($email, $zipfile, $password, $subject, $body, $study_center); // send email
				sendPassword($phone_number, $password, $study_center, $exam_session, $exam_day); //send sms

				$check = "SELECT * FROM files_stage WHERE study_center = '$study_center' AND student_center_name = '$center' AND exam_session = '$exam_session' AND exam_day = '$exam_day' AND file_name = '$zipfile'";

				if(mysqli_num_rows(mysqli_query($connection, $check)) == 0)  {
					$store = "INSERT INTO files_stage (study_center,student_center_name,exam_session,exam_day,password,file_name,sem) VALUES ('$study_center', '$center', '$exam_session', '$exam_day', '$password', '$zipfile', '222')";
					mysqli_query($connection, $store) or die(mysqli_error($connection));
				}

				if(file_exists($zipfile)){
					// unlink($zipfile); // uncomment if you want to delete the files
				}
			}
		}
	}

	if(mysqli_num_rows($query_data) > 0)  {
		echo "Password and attachment sent to email";
	} else {
		echo "No data found";
	}
} catch(Exception $e){
	echo $e->getMessage();
}

function sendPassword($phone_number, $password, $study_center, $exam_session, $exam_day){
	$message_data = "Dear ". $study_center. " Director,\n Your Day ".$exam_day." " .$exam_session." Session Password is:  ". $password;
	try{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://mps.digitalpulseapi.net/1.0/send-sms/anq',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
				"sender": "NOUN",
				"message": "'.$message_data.'",
				"receiver": "'.$phone_number.'"}',
			CURLOPT_HTTPHEADER => array(
				'api-key: N1Y8NIuMPhV5kDwCQgBxEA==',
				'Content-Type: application/json'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
	} catch(Exception $e){
	}
}

function securityCode($dir, $object, $security_code, $connection) {
    // Create new Landscape PDF
    $pdf = new FPDI('p');

    // Reference the PDF you want to use (use relative path)
    $pageCount = $pdf->setSourceFile($dir);

    // Import the first page from the PDF and add to dynamic PDF
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        // import a page
        $templateId = $pdf->importPage($pageNo);

        // get the size of the imported page
        $size = $pdf->getTemplateSize($templateId);

        // create a page (landscape or portrait depending on the imported page size)
        if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', array($size['width'], $size['height']));
        } else {
            $pdf->AddPage('P', array($size['width'], $size['height']));
        }

        // Use the imported page as the template
        $pdf->useTemplate($templateId);

        // Set the default font to use
        $pdf->SetFont('Helvetica');
        $pdf->SetTextColor(255, 255, 255); // Set to Black color

        // Adding a Cell using:
        // $pdf->Cell($width, $height, $text, $border, $fill, $align);

        // First box - the user's Name
        if ($pageNo === 1) {
            $pdf->SetFontSize('12'); // Set font size
            $pdf->SetXY(10, 10); // Set the position of the box
            $pdf->Cell(0, 10, 'Study Center Code: ' . $object['study_center_code'], 0, 0, 'L'); // Add the text, align to Center of cell

            $pdf->SetXY(10, 20); // Set the position of the box
            $pdf->Cell(0, 10, 'Study Center Name: ' . $object['study_center'], 0, 0, 'L'); // Add the text, align to Center of cell

            $pdf->SetXY(10, 30); // Set the position of the box
            $pdf->Cell(0, 10, 'Date: ' . date('y-m-d'), 0, 0, 'L'); // Add the text, align to Center of cell
        }
    }

    $pdf->Output('F', $dir);
    //storeSecurityCode($security_code,$object['id'],$connection); // store to DB
}

function sendEmail($email, $attachment, $password, $subject, $body, $study_center) {
   	$mailer = require ('mailerbatch1.php')
}

function dd($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    return false;
}
?>
<br>
<a href="sentitems.php">View Files and Passwords</a>

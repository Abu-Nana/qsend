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
	$script = "select student_registrations.*,study_centers.study_center_code, study_centers.study_centre_email,study_centers.phone_number from `student_registrations` inner join `study_centers` on `study_centers`.`study_center_code` = `student_registrations`.`study_center_code` where `exam_session` = '".$exam_session ."' 
		and `exam_day` = '".$day ."' group by `study_centers`.`study_center_code`, `student_registrations`.`course`";
		$query_data = mysqli_query($connection,$script);
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
			$zipfile = "2022_2_compressed/".$file_name.".zip";
			$password = "dea".rand(1,1000000);
			$status = false;

			if($zip->open($zipfile, ZipArchive::CREATE  | ZipArchive::OVERWRITE) === TRUE){
				$zip->setPassword($password);
				foreach ($value as  $obj) {

					$dir = "2022_2/".$obj['course'].".pdf";
					if(file_exists($dir)){
						securityCode($dir,$obj,$date,$connection); // put security
						$zip->addFile($dir);
						$zip->setEncryptionName($dir, ZipArchive::EM_AES_256);
						$status = true;
					}
				}
				$zip->close();
				if($status == true){ // check if file exist
		            $email = isset($centers[$center][0]['study_centre_email']) ? $centers[$center][0]['study_centre_email'] : '';
		            $phone_number = isset($centers[$center][0]['phone_number']) ? $centers[$center][0]['phone_number'] : '';
		            sendEmail($email,$zipfile,$password,$subject,$body,$study_center); // send email
		            sendPassword($phone_number,$password,$study_center,$exam_session,$exam_day); //send sms
	             $check = "SELECT * from files
				              where study_center = '$study_center'
				              and student_center_name = '$center'
				              and exam_session = '$exam_session'
				              and exam_day = '$exam_day'
				              and file_name = '$zipfile'
		        ";
		         if(mysqli_num_rows(mysqli_query($connection,$check)) == 0)  {
						$store = "INSERT INTO files (study_center,student_center_name,exam_session	,exam_day,password,file_name,sem) values(
						           '$study_center' ,'$center','$exam_session','$exam_day','$password','$zipfile','222'
						)";
						mysqli_query($connection,$store)  or die(mysqli_error($connection));
			     }
		            if(file_exists($zipfile)){
		            	// unlink($zipfile); uncomment if you want to delete the files
		            }
		        }
			}
		}
		if(mysqli_num_rows($query_data) > 0)  {
			echo "password and attachment sent to email";
		}else{
			echo "no data found";
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
/*function sendPassword($phone_number,$password,$study_center){
	$username   = "noundea"; //  sandbox is for test mode
	$apiKey     = "2caa30889be001c122bf21423f9498346db0c989c373e6387bddf0c10e0827ee";
	$AT = new AfricasTalking($username, $apiKey);
	$sms = $AT->sms();
	$message_data = "Dear ". $study_center. "Director,\n
						Session Code:  ". $password;
	$phone_number = "+".$phone_number;
	$from       = "NOUNDEA"; // Set your shortCode or senderId
	try {
	    $result = $sms->send([
	        'to'      => $phone_number,
	        'message' => $message_data,
	        'from'    => $from
	    ]);
	} catch (Exception $e) {
	    echo "Error: ".$e->getMessage();
	}
}
*/

function sendPassword($phone_number,$password,$study_center,$exam_session,$exam_day){
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
	//dd($response);
	}catch(Exception $e){
	}
}

function securityCode($dir,$object,$date,$connection){
	$code = ":".rand(1,100000000);
	$security_code = $date." ".$code;
	// Create new Landscape PDF
	$pdf = new FPDI('p');

	// Reference the PDF you want to use (use relative path)
	$pagecount = $pdf->setSourceFile( $dir );

	// Import the first page from the PDF and add to dynamic PDF
	$tpl = $pdf->importPage(1);
	$pdf->AddPage();

	// Use the imported page as the template
	$pdf->useTemplate($tpl);

	// Set the default font to use
	$pdf->SetFont('Helvetica');
	$pdf->SetTextColor(255,255,255);//Set to Black color

	// adding a Cell using:
	// $pdf->Cell( $width, $height, $text, $border, $fill, $align);

	// First box - the user's Name
	$pdf->SetFontSize('.8'); // set font size
	$pdf->SetXY(0, 0); // set the position of the box
	$pdf->Cell(0, 3, $security_code, 0, 0, 'L'); // add the text, align to Center of cell
	// render PDF to browser
	$pdf->Output('F',$dir);
	storeSecurityCode($code,$object['id'],$connection); // store to DB
}
function storeSecurityCode($code ,$student_registration_id,$connection){
	$store = "INSERT INTO security_codes (security_code,student_registration_id) values(
				'$code' ,$student_registration_id)";
	mysqli_query($connection,$store)  or die(mysqli_error($connection));
}
function sendEmail($email,$attachment,$password,$subject,$body,$study_center){
	$mailer = require ('mailer.php');
}
function dd($data){
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
	return false;
}

?>
<br>
<a href="sentitems.php">View Files and Passwords</a>
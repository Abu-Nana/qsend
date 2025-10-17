<?php
$connection = require 'connection.php';
require 'vendor3/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

try{
	$exam_session = $_POST['session'];
	$day = $_POST['day'];
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	if(!isset($exam_session)){
		echo "please select session exam";
		return false;
	}
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
			$zipfile = "compressed/".$file_name.".zip";
			$password = "pdf".rand(1,1000000);
			$status = false;

			if($zip->open($zipfile, ZipArchive::CREATE  | ZipArchive::OVERWRITE) === TRUE){
				$zip->setPassword($password);
				foreach ($value as  $obj) {
					$dir = "Files/".$obj['course'].".pdf";
					if(file_exists($dir)){
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
		            sendPassword($phone_number,$password,$study_center); //send sms
	             $check = "SELECT * from files
				              where study_center = '$study_center'
				              and student_center_name = '$center'
				              and exam_session = '$exam_session'
				              and exam_day = '$exam_day'
				              and file_name = '$zipfile'";
		         if(mysqli_num_rows(mysqli_query($connection,$check)) == 0)  {
						$store = "INSERT INTO files (study_center,student_center_name,exam_session	,exam_day,password,file_name) values(
						           '$study_center' ,'$center','$exam_session','$exam_day','$password','$zipfile'
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
function sendPassword($phone_number,$password,$study_center){
	$username   = "noundea"; //  sandbox is for test mode
	$apiKey     = "6b5db275877d67c5fc1875ce06c44d7c82d53534870b4171b081ba711a3350e7";
	$AT = new AfricasTalking($username, $apiKey);
	$sms = $AT->sms();
	$message_data = "Dear ". $study_center. "Director,\n
						Please here is your password  ". $password;
						echo $message_data;
	$phone_number = "+".$phone_number;
	$from       = ""; // Set your shortCode or senderId
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

function sendEmail($email,$attachment,$password,$subject,$body,$study_center){
	$mailer = require ('mailer.php');
}

?>
<br>
<a href="sentitems.php">View Files and Passwords</a>
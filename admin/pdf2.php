// <?php
// $connection = require 'connection.php';
// //require  'vendor4/twilio/src/Twilio/autoload.php';
// //use Twilio\Rest\Client;
// try{
// $exam_session = $_POST['session'];
// $day = $_POST['day'];
// $subject = $_POST['subject'];
// $body = $_POST['body'];
// if(!isset($exam_session)){
// 	echo "please select session exam";
// 	return false;
// }
// $script = "select student_registrations.*,study_centers.study_center_code, study_centers.study_centre_email,study_centers.phone_number from `student_registrations` inner join `study_centers` on `study_centers`.`study_center_code` = `student_registrations`.`study_center_code` where `exam_session` = '".$exam_session ."' 
// 	and `exam_day` = '".$day ."' group by `study_centers`.`study_center_code`, `student_registrations`.`course`";
// 	$query_data = mysqli_query($connection,$script);
// 	$zip = new \ZipArchive();
// 	$centers = [];
// 	if(mysqli_num_rows($query_data) > 0)  {
// 		while($obj = mysqli_fetch_assoc($query_data)){
// 			$centers[$obj['study_center_code']][] = $obj;
// 		}
// 	}
// 	foreach ($centers as $center => $value) {
// 		$exam_session = isset($centers[$center][0]['exam_session']) ? $centers[$center][0]['exam_session'] : '_session';
// 		$study_center = isset($centers[$center][0]['study_center']) ? $centers[$center][0]['study_center'] : '_session';
// 	    $exam_day = isset($centers[$center][0]['exam_day']) ? $centers[$center][0]['exam_day'] : '_session';
//         $file_name = str_replace(':','_',str_replace(' ','_',$study_center.$center."_".$exam_session. '_'. $exam_day));
// 		$zipfile = "2023_1_compressed/".$file_name.".zip";
// 		$password = "dea".rand(1,1000000);
// 		$status = false;

// 		if($zip->open($zipfile, ZipArchive::CREATE  | ZipArchive::OVERWRITE) === TRUE){
// 			$zip->setPassword($password);
// 			foreach ($value as  $obj) {
// 				$dir = "2023_1/".$obj['course'].".pdf";
// 				if(file_exists($dir)){
// 					$zip->addFile($dir);
// 					$zip->setEncryptionName($dir, ZipArchive::EM_AES_256);
// 					$status = true;
// 				}
// 			}
// 			$zip->close();
// 			if($status == true){ // check if file exist
// 	            $email = isset($centers[$center][0]['study_centre_email']) ? $centers[$center][0]['study_centre_email'] : '';
// 	            $phone_number = isset($centers[$center][0]['phone_number']) ? $centers[$center][0]['phone_number'] : '';
// 	            sendEmail($email,$zipfile,$password,$subject,$body,$study_center); // send email
// 	           // sendPassword($phone_number,$password,$study_center); //send sms
//              $check = "SELECT * from files
// 			              where study_center = '$study_center'
// 			              and student_center_name = '$center'
// 			              and exam_session = '$exam_session'
// 			              and exam_day = '$exam_day'
// 			              and file_name = '$zipfile'
// 	        ";
// 	         if(mysqli_num_rows(mysqli_query($connection,$check)) == 0)  {
// 					$store = "INSERT INTO files (study_center,student_center_name,exam_session	,exam_day,password,file_name,sem) values(
// 					           '$study_center' ,'$center','$exam_session','$exam_day','$password','$zipfile','231'
// 					)";
// 					mysqli_query($connection,$store)  or die(mysqli_error($connection));
// 		     }
// 	            if(file_exists($zipfile)){
// 	            	// unlink($zipfile); uncomment if you want to delete the files
// 	            }
// 	        }
// 		}
// 	}
// 		if(mysqli_num_rows($query_data) > 0)  {
// 			echo "password and attachment sent to email";
// 		}else{
// 			echo "no data found";
// 		}
// 	}catch(Exception $e){
// 		echo $e->getMessage();
// 	}
// /*function sendPassword($phone_number,$password,$study_center){
// 	$phone_number = "+".$phone_number;
// 	$sid = "ACea7b40b4337ac14a0930148db4603728"; //Find your Account SID and Auth Token at twilio.com/console
// 	$token = "1976eb9367b858de52a1d5b355060c1d"; // and set the environment variables. See http://twil.io/secure
// 	$twilio = new Client($sid, $token);
// 	$twillio_phone = "+17198243440"; // this is trial
// 	$message_data = "Dear ". $study_center. "Director, \n
// 						Your Sample. ". $day. " ". $exam_session. "Password: " .$password.;
// 	$message = $twilio->messages
// 	                  ->create($phone_number, // to
// 	                           ["body" => $message_data, "from" => $twillio_phone]
// 	                  );
// }
// */
// function sendEmail($email,$attachment,$password,$subject,$body,$study_center){
// 	$mailer = require ('mailer2.php');
// }
// <br>
// <a href="sentitems.php">View Files and Passwords</a>
// ?>

<?php
$connection = require 'connection.php';
require 'vendor3/autoload.php';
require 'mailer3.php';

try {
    $exam_session = $_POST['session'];
    $day = $_POST['day'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    if (!isset($exam_session)) {
        echo "please select session exam";
        return false;
    }

    $script = "SELECT student_registrations.*, study_centers.study_center_code, study_centers.study_centre_email, study_centers.phone_number,study_centers.director
               FROM `student_registrations` 
               INNER JOIN `study_centers` ON `study_centers`.`study_center_code` = `student_registrations`.`study_center_code` 
               WHERE `exam_session` = '" . $exam_session . "' AND `exam_day` = '" . $day . "' 
               GROUP BY `study_centers`.`study_center_code`, `student_registrations`.`course`";
    $query_data = mysqli_query($connection, $script);

//     $script = "SELECT study_centers.study_center_code, study_centers.study_centre_email, study_centers.phone_number, study_centers.director
//            FROM student_registrations
//            INNER JOIN study_centers ON study_centers.study_center_code = student_registrations.study_center_code
//            WHERE exam_session = '" . $exam_session . "' AND exam_day = '" . $day . "' 
//            GROUP BY study_centers.study_center_code, student_registrations.course";

// $stmt = mysqli_prepare($connection, $script);
// mysqli_stmt_bind_param($stmt, "ss", $exam_session, $day);
// mysqli_stmt_execute($stmt);
// $query_data = mysqli_stmt_get_result($stmt);


    $zip = new \ZipArchive();
    $centers = [];

    if (mysqli_num_rows($query_data) > 0) {
        while ($obj = mysqli_fetch_assoc($query_data)) {
            $centers[$obj['study_center_code']][] = $obj;
        }
    }

    foreach ($centers as $center => $value) {
        $exam_session = isset($centers[$center][0]['exam_session']) ? $centers[$center][0]['exam_session'] : '_session';
        $study_center = isset($centers[$center][0]['study_center']) ? $centers[$center][0]['study_center'] : '_session';
        $exam_day = isset($centers[$center][0]['exam_day']) ? $centers[$center][0]['exam_day'] : '_session';
        $director = isset($centers[$center][0]['director']) ? $centers[$center][0]['director'] : '_session';
        $file_name = str_replace(':', '_', str_replace(' ', '_', $study_center . $center . "_" . $exam_session . '_' . $exam_day));
        $zipfile = "2023_1_compressed/" . $file_name . ".zip";
        $password = "dea" . rand(1, 1000000);
        $status = false;


        if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->setPassword($password);
            foreach ($value as $obj) {
                $dir = "2023_1/" . $obj['course'] . ".pdf";
                if (file_exists($dir)) {
                    $zip->addFile($dir);
                    $zip->setEncryptionName($dir, ZipArchive::EM_AES_256);
                    $status = true;
                }
            }
            $zip->close();

            if ($status) {
                $email = isset($centers[$center][0]['study_centre_email']) ? $centers[$center][0]['study_centre_email'] : '';
                $attachments = [$zipfile];
                                sendEmailWithAttachments($email, $attachments, $password, $subject, $body, $study_center);
                $check = "SELECT * FROM files
                          WHERE study_center = '$study_center'
                          AND student_center_name = '$center'
                          AND exam_session = '$exam_session'
                          AND exam_day = '$exam_day'
                          AND file_name = '$zipfile'";

                if (mysqli_num_rows(mysqli_query($connection, $check)) == 0) {
                    $store = "INSERT INTO files (study_center, student_center_name, exam_session, exam_day, password, file_name, sem) 
                              VALUES ('$study_center', '$center', '$exam_session', '$exam_day', '$password', '$zipfile', '231')";
                    mysqli_query($connection, $store) or die(mysqli_error($connection));
                }

                if (file_exists($zipfile)) {
                    // unlink($zipfile); // uncomment if you want to delete the files
                }
            }
        }
    }

    if (mysqli_num_rows($query_data) > 0) {
        echo "password and questions sent to email";
    } else {
        echo "no data found";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<br>
<a href="sentitems.php">View Files and Passwords</a>



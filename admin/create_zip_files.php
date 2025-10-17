<?php
// Secure Database Connection
$connection = require 'connection.php';
require 'vendor3/autoload.php';

try {
    // Validate inputs
    $exam_session = isset($_POST['session']) ? $_POST['session'] : null;
    $day = isset($_POST['day']) ? $_POST['day'] : null;

    if (!$exam_session || !$day) {
        throw new Exception("Please select session exam and day.");
    }

    $query = "SELECT student_registrations.*, study_centers.study_center_code, study_centers.study_centre_email, study_centers.phone_number 
              FROM student_registrations 
              INNER JOIN study_centers ON study_centers.study_center_code = student_registrations.study_center_code 
              WHERE exam_session = '".$exam_session."' AND exam_day = '".$day."'  
              GROUP BY study_centers.study_center_code, student_registrations.course";
    
    $query_data = $connection->query($query);

    if ($query_data->num_rows > 0) {
        $centers = [];
        while ($obj = $query_data->fetch_assoc()) {
            $centers[$obj['study_center_code']][] = $obj;
        }

        $count = 1; // Initialize the serial number

        foreach ($centers as $center => $value) {
            $zipfile = "all_zipped/".$exam_session."_".$day."_".$center.".zip";
            if (!file_exists($zipfile)) {
                $zip = new ZipArchive();
                if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    $password = "dea".rand(1,1000000);
                    $zip->setPassword($password);

                    foreach ($value as $obj) {
                        $dir = "2023_1/".$obj['course'].".pdf";
                        if (file_exists($dir)) {
                            $zip->addFile($dir);
                            $zip->setEncryptionName($dir, ZipArchive::EM_AES_256);
                        }
                    }

                    $zip->close();

                    $email = isset($obj['study_centre_email']) ? $obj['study_centre_email'] : '';
                    $phone_number = isset($obj['phone_number']) ? $obj['phone_number'] : '';

                    $insert_query = "INSERT INTO files (study_center, student_center_name, exam_session, exam_day, password, file_name, sem, created_at) 
                                     VALUES ('".$obj['study_center']."', '".$obj['study_center_code']."', '".$exam_session."', '".$day."', '".$password."', '".$zipfile."', '232', NOW())";

                    $connection->query($insert_query) or die($connection->error);

                    // Display the success message with the format
                    echo "S/N: ".$count.", Centre Code/Centre Name: ".$center.", Student Center Name: ".$obj['student_center_name'].", Time created: ".date("Y-m-d H:i:s")."<br>";
                    $count++;

                } else {
                    throw new Exception("Failed to create zip file.");
                }
            } else {
                echo "File with the name ".$zipfile." already exists. Skipping creation. Serial Number: ".$count."<br>";
                $count++;
            }
        }

        // Include a link for CSV export
        echo "<a href='export.php?exam_day=".$day."&section=".$center."'>Export to CSV</a>";
        echo "<a href='qsendbacth1_mail.php'>Send Questions Now</a>";

    } 
            
    else {
        throw new Exception("No data found.");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
$connection->close();
?>

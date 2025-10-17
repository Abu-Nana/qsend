<?php
$connection = require 'connection.php';
require 'vendor3/autoload.php';
use setasign\Fpdi\Fpdi;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

try {
    $exam_session = $_POST['exam_session'];
    $day = $_POST['exam_day'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    if (!isset($exam_session)) {
        echo "Please select an exam session.";
        return false;
    }

    // Your database table name for files_stage may be different, update it if needed
    $files_table = "files";

    $script = "SELECT s.id, s.matric_number, s.study_center, s.study_center_code, s.course, s.exam_day, s.exam_session, s.exa_date, c.study_center_code, c.study_centre_email, c.phone_number, c.director 
                FROM student_registrations s
                INNER JOIN study_centers_mondays c 
                ON c.study_center_code = s.study_center_code 
                WHERE s.exam_session = ? 
                AND s.exam_day = ?
                GROUP BY c.study_center_code, s.course";

    $stmt = mysqli_prepare($connection, $script);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $exam_session, $day);
        mysqli_stmt_execute($stmt);
        $query_data = mysqli_stmt_get_result($stmt);

        if (!$query_data) {
            printf("Error: %s\n", mysqli_error($connection)); // Display any SQL errors
            exit();
        }

        $centers = [];
        $status_message = '';

        if (mysqli_num_rows($query_data) > 0) {
            while ($obj = mysqli_fetch_assoc($query_data)) {
                $centers[$obj['study_center_code']][] = $obj;
            }
        } else {
            echo "No data found in the database for the specified query.";
            exit();
        }

        $centers_sent = []; // Initializing an array to store the sent centers

        foreach ($centers as $center => $value) {
            $exam_session = isset($centers[$center][0]['exam_session']) ? $centers[$center][0]['exam_session'] : '_session';
            $study_center = isset($centers[$center][0]['study_center']) ? $centers[$center][0]['study_center'] : '_session';
            $exam_day = isset($centers[$center][0]['exam_day']) ? $centers[$center][0]['exam_day'] : '_session';
            $file_name = str_replace(':', '_', str_replace(' ', '_', $study_center . $center . "_" . $exam_session . '_' . $exam_day));
            $file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_name); // Removing special characters from the file name
            $folder_path = "temp/" . $file_name;
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            $zipfile = "deacompress/" . $file_name . ".zip";
            $password = "dea" . rand(1, 1000000);
            $status = false;
            $zip = new ZipArchive();
            if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($value as $obj) {
                    $source_file = "DEASemester/" . $obj['course'] . ".pdf";
                    $destination_file = $folder_path . "/" . $obj['course'] . ".pdf";
                    if (file_exists($source_file)) {
                        copy($source_file, $destination_file);
                        $pdf = new FPDI();
                        $pageCount = $pdf->setSourceFile($destination_file);
                        for ($i = 1; $i <= $pageCount; $i++) {
                            $tplIdx = $pdf->importPage($i);
                            $pdf->AddPage();
                            $pdf->useTemplate($tplIdx, 10, 10, 200);
                            $pdf->SetFont('Arial', '', 5);
                            $pdf->SetTextColor(255, 255, 255); // Set text color to white
                            $pdf->SetXY(10, 10);
                            $pdf->Write(0, "Study Centre: " . $study_center . " (" . $center . ")");
                                               }
                        $pdf->Output($destination_file, 'F');
                        $zip->addFile($destination_file, basename($destination_file));
                        $zip->setEncryptionName(basename($destination_file), ZipArchive::EM_AES_256, $password); // Set password for each file
                        $status = true;
                    }
                }
                $zip->close();

                // Storing data in files_stage table
                $study_center = $value[0]['study_center'];
                $exam_session = $value[0]['exam_session'];
                $exam_day = $value[0]['exam_day'];
                $username = $user['username'];
                $firstName = $user['firstname'];
                $lastName = $user['lastname'];
                $fullName = $username . ' - ' . $firstName . ' ' . $lastName;
                $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown IP';

                $store = "INSERT INTO $files_table 
                    (study_center, student_center_name, exam_session, exam_day, password, file_name, sem, sentby, ip_address) 
                    VALUES (?, ?, ?, ?, ?, ?, '251', ?, ?)";
                
                $stmt_store = mysqli_prepare($connection, $store);
                mysqli_stmt_bind_param($stmt_store, "ssssssss", $study_center, $center, $exam_session, $exam_day, $password, $zipfile, $fullName, $ipAddress);
                mysqli_stmt_execute($stmt_store);
                mysqli_stmt_close($stmt_store);

                if (file_exists($folder_path)) {
                    deleteDirectory($folder_path); // Function to delete directory
                }

                // Send the generated zip file to the study center email
                $email = isset($centers[$center][0]['study_centre_email']) ? $centers[$center][0]['study_centre_email'] : '';
                $attachment = $zipfile;
                $password = $password;
                $study_center_name = $study_center;
                $counter = 1;
                sendEmail($email, $attachment, $password, $subject, $body, $study_center_name); // Send email
                $director = $value[0]['director']; // Get the director's name for the status message
                $centers_sent[] = "Questions sent to: " .$counter. " - " .$study_center." : Centre Director:  ".$director . " Email: " . $email;
                $counter++;
            }
        }

        if (mysqli_num_rows($query_data) > 0) {
            $status_message = "Questions Have Been Sent Successfully.";
        } else {
            $status_message = "No data found.";
        }

        // Display the status message in a modal
       echo "
    <div class='modal' tabindex='-1' role='dialog' id='statusModal'>
      <div class='modal-dialog' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Status Message</h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <div class='modal-body'>
            <p>$status_message</p>
            <p>" . implode("<br>", $centers_sent) . "</p> <!-- Display the list of centers -->
          </div>
        </div>
      </div>
    </div>
    ";
    } else {
        printf("Error: %s\n", mysqli_error($connection)); // Display any SQL errors
        exit();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    // Log the error to a file or a logging service for debugging purposes
}

function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

function sendEmail($email, $attachment, $password, $subject, $body, $study_center)
{
    // Include the mailerbatch1.php file
    $mailer = require ('mailerbatch1.php');

    // Additional code for sending email with the mailerbatch1 functionality
    // You can use the mailerbatch1's methods here for sending the email
}


    $username = $user['username'];
    $firstName = $user['firstname'];
    $lastName = $user['lastname'];

    // Combine the first name and last name to form the full name
    $fullName = $username . ' - ' . $firstName . ' ' . $lastName;
    echo "This session is sent by: "  . $fullName . "<br>";
?>

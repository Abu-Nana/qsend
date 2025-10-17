<?php
// Load the database configuration file
// Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "25#wpdx20@DEA";
$dbName     = "NBRRI";

// Create database connection
 $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if(isset($_POST['bulkupdate'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
               $id   = $line[0];
                $email  = $line[1];
                $IPPIS_ID  = $line[2];
                $legacy_ID = $line[3];
                 $Full_Name = $line[4];
                 $Bank_Name = $line[5];
                 $Account_Number = $line[6];
                 $Job_Title = $line[7];
                 $School = $line[8];
                 $Department = $line[9];
                 $Location = $line[10];
                 $Unit = $line[11];
                 $Grade = $line[12];
                 $Grade_Step = $line[13];
                 $gender = $line[14];
                 $Pension_Administrator = $line[15];
                 $Pension_PIN = $line[16];
                 $TIN = $line[17];
                 $date_of_appointment = $line[18];
                 $date_of_birth = $line[19];
                 $union_name = $line[20];
                 $tax_state = $line[21];
                 $nhf_number = $line[22];
                 
                
                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT id FROM staff_record WHERE id = '".$line[0]."'";
                $prevResult = $db->query($prevQuery);
                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $db->query("UPDATE staff_record SET email = '".$email."', IPPIS_ID = '".$IPPIS_ID."', legacy_ID = '".$legacy_ID."', Full_name = '".$Full_Name."' , Bank_Name = '".$Bank_Name."', Account_NUmber = '".$Account_Number."', Job_Title = '".$Job_Title."', School = '".$School."', Department = '".$Department."', Location = '".$Location."' , Unit = '".$Unit."', Grade = '".$Grade."' , Grade_Step = '".$Grade_Step."', gender = '".$gender."', Pension_Administrator = '".$Pension_Administrator."',Pension_PIN = '".$Pension_PIN."', TIN = '".$TIN."', date_of_appointment = '".$date_of_appointment."', date_of_birth = '".$date_of_birth."', union_name = '".$union_name."', tax_state = '".$tax_state."', nhf_number = '".$nhf_number."' WHERE id = '".$id."'");
                }else{
                    // Insert member data in the database
                    $db->query("INSERT INTO staff_record(id, email, IPPIS_ID, legacy_ID, Full_Name, Bank_Name, Account_Number, Job_Title, School, Department, Location, Unit, Grade, Grade_Step, gender, Pension_Administrator, Pension_PIN, TIN, date_of_appointment, date_of_birth, union_name, tax_state, nhf_number) VALUES ('".$id."',''".$email."', '".$IPPIS_ID."', '".$legacy_ID."','".$Full_Name."' ,  '".$Bank_Name."',  '".$Account_Number."', '".$Job_Title."', '".$School."',  '".$Department."', '".$Location."' ,  '".$Unit."',  '".$Grade."' ,  '".$Grade_Step."', '".$gender."',  '".$Pension_Administrator."', '".$Pension_PIN."', '".$TIN."',  '".$date_of_appointment."',  '".$date_of_birth."',  '".$union_name."', '".$tax_state."',  '".$nhf_number."' WHERE id = '".$id."')");
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: staff_bulk_update.php".$qstring);
<?php
// Load the database configuration file
// Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "25#wpdx20@DEA";
$dbName     = "2022";

// Create database connection
 $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if(isset($_POST['importSubmit2'])){
    
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
                $Reference_ID  = $line[1];
                $TAX_ID  = $line[2];
                $JAN_Retro_Arrears = $line[3];
                $APRIL_Retro_Arrears = $line[4];
                 $AUG_RETRO_ARREARS = $line[5];
                 $CONTEDISS_Cons_Salary = $line[6];
                 $CONTISS_Cons_Salary = $line[7];
                 $CONUASS_Cons_Salary = $line[8];
                 $FEB_Retro_Arrears = $line[9];
                 $JULY_RETRO_ARREARS = $line[10];
                 $JUNE_Retro_Arrears = $line[11];
                 $MARCH_Retro_Arrears = $line[12];
                 $May_Retro_Arrears = $line[13];
                 $NOV_RETRO_ARREARS = $line[14];
                 $OCT_RETRO_ARREARS = $line[15];
                 $SEP_RETRO_ARREARS = $line[16];
                 $Total_Gross = $line[17];
                 $FEDERAL_MORTGAGE_BANK_OF_NIGERIA = $line[18];
                 $NHF = $line[19];
                 $NHF_DED_JAN_ARREARS = $line[20];
                 $NHF_DED_JULY_ARREARS = $line[21];
                 $NHF_DED_APR_ARREARS = $line[22];
                 $NHF_DED_AUG_ARREARS = $line[23];
                 $NHF_DED_FEB_ARREARS = $line[24];
                 $NHF_DED_MAR_ARREARS = $line[25];
                 $NHF_DED_JUNE_ARREARS = $line[26];
                 $NHF_DED_SEP_ARREARS = $line[27];
                 $NHF_DED_MAY_ARREARS = $line[28];
                 $NHF_DED_NOV_ARREARS = $line[29];
                 $NHF_DED_OCT_ARREARS = $line[30];
                 $NOUN_CARLISLE = $line[31];
                 $NOUN_BURSARY_7167WEMA = $line[32];
                 $NOUN_CETED_COOP_9657IBTC = $line[33];
                 $NOUN_DEBT_REFUND_1018CBN = $line[34];
                 $NOUN_MICROFINANCE_9355ZENITH = $line[35];
                 $NOUN_MEDICAL_LOAN_1018CBN = $line[36];
                 $NOUN_MULTIPUR_COOP_3491ZENITH = $line[37];
                 $NOUN_RENT_DEDUCTION_1018CBN = $line[38];
                 $NOUN_SALARY_ADVANCE_1018CBN = $line[39];
                 $OVERPAYMENT = $line[40];
                 $PENSION = $line[41];
                 $PENSION_DED_JAN_ARREARS = $line[42];
                 $PENSION_DED_APR_ARREARS = $line[43];
                 $PENSION_DED_AUG_ARREARS = $line[44];
                 $PENSION_DED_FEB_ARREARS = $line[45];
                 $PENSION_DED_JULY_ARREARS = $line[46];
                 $PENSION_DED_JUNE_ARREARS = $line[47];
                 $PENSION_DED_MAR_ARREARS = $line[48];
                 $PENSION_DED_MAY_ARREARS = $line[49];
                 $PENSION_DED_NOV_ARREARS = $line[50];
                 $PENSION_DED_OCT_ARREARS = $line[51];
                 $PENSION_DED_SEP_ARREARS = $line[52];
                 $UNION_DUES = $line[53];
                 $Income_Tax = $line[54];
                 $Total_Deduction = $line[55];
                 $Net_Pay = $line[56];
                $Resposibility_allowance = $line[57];
                $Unspecified_arrears = $line[58];
                $Salary_Refund = $line[59];
                $Unspecified_Deduction = $line[60];
                $interdiction = $line[61];
                $FMBN_RENT_TO_OWN = $line[62];
                $year = $line[63];
                $month = $line[64];
                
                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT id FROM payslip WHERE ID = '".$line[1]."'";
                $prevResult = $db->query($prevQuery);
                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $db->query("UPDATE members SET name = '".$name."', phone = '".$phone."', status = '".$status."', modified = NOW() WHERE email = '".$email."'");
                }else{
                    // Insert member data in the database
                    $db->query("delete from payslip(ID,Reference_ID,TAX_ID,JAN_Retro_Arrears,APRIL_Retro_Arrears,AUG_RETRO_ARREARS,CONTEDISS_Cons_Salary,CONTISS_Cons_Salary,CONUASS_Cons_Salary,FEB_Retro_Arrears,JULY_RETRO_ARREARS,JUNE_Retro_Arrears,MARCH_Retro_Arrears,May_Retro_Arrears,NOV_RETRO_ARREARS,OCT_RETRO_ARREARS,SEP_RETRO_ARREARS,Total_Gross,FEDERAL_MORTGAGE_BANK_OF_NIGERIA,NHF,NHF_DED_JAN_ARREARS,NHF_DED_JULY_ARREARS,NHF_DED_APR_ARREARS,NHF_DED_AUG_ARREARS,NHF_DED_FEB_ARREARS,NHF_DED_MAR_ARREARS,NHF_DED_JUNE_ARREARS,NHF_DED_SEP_ARREARS,NHF_DED_MAY_ARREARS,NHF_DED_NOV_ARREARS,NHF_DED_OCT_ARREARS,NOUN_CARLISLE,NOUN_BURSARY_7167WEMA,NOUN_CETED_COOP_9657IBTC,NOUN_DEBT_REFUND_1018CBN,NOUN_MICROFINANCE_9355ZENITH,NOUN_MEDICAL_LOAN_1018CBN,NOUN_MULTIPUR_COOP_3491ZENITH,NOUN_RENT_DEDUCTION_1018CBN,NOUN_SALARY_ADVANCE_1018CBN,OVERPAYMENT,PENSION,PENSION_DED_JAN_ARREARS,PENSION_DED_APR_ARREARS,PENSION_DED_AUG_ARREARS,PENSION_DED_FEB_ARREARS,PENSION_DED_JULY_ARREARS,PENSION_DED_JUNE_ARREARS,PENSION_DED_MAR_ARREARS,PENSION_DED_MAY_ARREARS,PENSION_DED_NOV_ARREARS,PENSION_DED_OCT_ARREARS,PENSION_DED_SEP_ARREARS,UNION_DUES,Income_Tax,Total_Deduction,Net_Pay,Resposibility_allowance,Unspecified_arrears,Salary_Refund,Unspecified_Deduction,interdiction,FMBN_RENT_TO_OWN,year,month) VALUES ('".$id."','".$Reference_ID."', '".$TAX_ID."','".$JAN_Retro_Arrears."','".$APRIL_Retro_Arrears."','".$AUG_RETRO_ARREARS."','".$CONTEDISS_Cons_Salary."','".$CONTISS_Cons_Salary."','".$CONUASS_Cons_Salary."','".$FEB_Retro_Arrears."','".$JULY_RETRO_ARREARS."','".$JUNE_Retro_Arrears."','".$MARCH_Retro_Arrears."','".$May_Retro_Arrears."','".$NOV_RETRO_ARREARS."','".$OCT_RETRO_ARREARS."','".$SEP_RETRO_ARREARS."','".$Total_Gross."','".$FEDERAL_MORTGAGE_BANK_OF_NIGERIA."','".$NHF."','".$NHF_DED_JAN_ARREARS."','".$NHF_DED_JULY_ARREARS."','".$NHF_DED_APR_ARREARS."','".$NHF_DED_AUG_ARREARS."','".$NHF_DED_FEB_ARREARS."','".$NHF_DED_MAR_ARREARS."','".$NHF_DED_JUNE_ARREARS."','".$NHF_DED_SEP_ARREARS."','".$NHF_DED_MAY_ARREARS."','".$NHF_DED_NOV_ARREARS."','".$NHF_DED_OCT_ARREARS."','".$NOUN_CARLISLE."','".$NOUN_BURSARY_7167WEMA."','".$NOUN_CETED_COOP_9657IBTC."','".$NOUN_DEBT_REFUND_1018CBN."','".$NOUN_MICROFINANCE_9355ZENITH."','".$NOUN_MEDICAL_LOAN_1018CBN."','".$NOUN_MULTIPUR_COOP_3491ZENITH."','".$NOUN_RENT_DEDUCTION_1018CBN."','".$NOUN_SALARY_ADVANCE_1018CBN."','".$OVERPAYMENT."','".$PENSION."','".$PENSION_DED_JAN_ARREARS."','".$PENSION_DED_APR_ARREARS."','".$PENSION_DED_AUG_ARREARS."','".$PENSION_DED_FEB_ARREARS."','".$PENSION_DED_JULY_ARREARS."','".$PENSION_DED_JUNE_ARREARS."','".$PENSION_DED_MAR_ARREARS."','".$PENSION_DED_MAY_ARREARS."','".$PENSION_DED_NOV_ARREARS."','".$PENSION_DED_OCT_ARREARS."','".$PENSION_DED_SEP_ARREARS."','".$UNION_DUES."','".$Income_Tax."','".$Total_Deduction."','".$Net_Pay."','".$Resposibility_allowance."','".$Unspecified_arrears."','".$Salary_Refund."','".$Unspecified_Deduction."','".$interdiction."','".$FMBN_RENT_TO_OWN."','".$year."','".$month."')");
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
header("Location: rawslip.php".$qstring);
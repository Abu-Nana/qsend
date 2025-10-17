<?php


        $conn = new mySqli('localhost', 'root', '25#wpdx20@DEA', '2022');
        if($conn->connect_error) {
            die('Could not connect to the database');
        }
    
   

if(isset($_POST['change'])){
		
		$username = $_POST['username'];
		$new_password = $_POST['new_password'];
		
		
			
				$new_password = password_hash($new_password, PASSWORD_DEFAULT);
			}

			$sql = "UPDATE admin SET password = '$new_password' WHERE username = '".$username."'";
			if($conn->query($sql)){
				echo "Update Suucess"
			
			
		
		
	

	
?>
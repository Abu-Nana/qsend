<?php
	include 'includes/session.php';

	if(isset($_POST['add_admin'])){
        $centre = $_POST['centre'];
		$director = $_POST['director'];
		$code = $_POST['code'];
		$email = $_POST['email'];
		$mobilenumber = $_POST['mobilenumber'];
        
		
		//creating employeeid
		$letters = '';
		$numbers = '';
		foreach (range('A', 'Z') as $char) {
		    $letters .= $char;
        }
		for($i = 0; $i < 10; $i++){
			$numbers .= $i;
		}
		$employee_id = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 9);
		// creating password 
        $password2 = password_hash($password, PASSWORD_DEFAULT);
		$sql = "INSERT INTO study_centers (study_center, director,study_center_code,study_centre_email,phone_number) VALUES ('$centre','$director', '$code', '$email', '$mobilenumber')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Study Centre Record added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
    }
	
	else{
		$_SESSION['error'] = 'Fill up add form first new';
	}

	header('location: centres.php');
?>
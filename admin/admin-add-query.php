<?php
	include 'includes/session.php';

	if(isset($_POST['add_admin'])){
		$id = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$filename = $_FILES['photo']['photo'];
		if(!empty($filename)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}
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
		//
         $password2 = password_hash($password, PASSWORD_DEFAULT);
		$sql = "INSERT INTO admin (id, firstname, lastname,  username, password, photo, created_on,photo) VALUES ('$id', '$firstname', '$lastname', '$username', '$password2', NOW(),'$filename')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Staff Record added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first new';
	}

	header('location: edit_admins.php');
?>
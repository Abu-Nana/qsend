<?php
	require_once 'conn.php';
	include 'includes/session.php';
	if(ISSET($_POST['update_admin'])){
		$user_id = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
        $password = $_POST['password'];
        
        
        $password2 = password_hash($password, PASSWORD_DEFAULT);
		
		
		$sql = "UPDATE `admin` SET `firstname` = '$firstname', `lastname` = '$lastname' , `password` = '$password2'  WHERE `id` = '$user_id'"; 
        if($conn->query($sql)){
			$_SESSION['success'] = 'Admin Record updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
		else{
		$_SESSION['error'] = 'Select Staff to edit first';
	}

	header('location: edit_admins.php');
?>



<?php
	require_once 'conn.php';
	
	if(ISSET($_POST['save'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$address = $_POST['address'];
		
		mysqli_query($conn, "INSERT INTO `user` VALUES('', '$firstname', '$lastname', '$address')") or die(mysqli_error());
		
		header("location: index.php");
	}
?>
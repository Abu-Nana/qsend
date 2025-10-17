<?php 
	session_start();
	$db = mysqli_connect('localhost', 'root', '25#wpdx20@DEA', '2022');

	// initialize variables
	$name = "";
	$address = "";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$name = $_POST['name'];
		$address = $_POST['address'];

		mysqli_query($db, "INSERT INTO yesr_test (name, address) VALUES ('$name', '$address')"); 
		$_SESSION['message'] = "Address saved"; 
		header('location: edit_test.php');
        
	}
if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
	$address = $_POST['address'];

	mysqli_query($db, "UPDATE yesr_test SET name='$name', address='$address' WHERE id=$id");
	$_SESSION['message'] = "Address updated!"; 
	header('location: edit_test.php');
}
if (isset($_GET['del'])) {
	$id = $_GET['del'];
	mysqli_query($db, "DELETE FROM yesr_test WHERE id=$id");
	$_SESSION['message'] = "Address deleted!"; 
	header('location: edit_test.php');
}
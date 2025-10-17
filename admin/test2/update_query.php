<?php
	require_once 'conn.php';
	
	if(ISSET($_POST['update'])){
		$user_id = $_POST['id'];
		$IPPIS_ID = $_POST['IPPIS_ID'];
		$Full_Name = $_POST['Full_Name'];
        
		
		
		mysqli_query($conn, "UPDATE `staff_record` SET `IPPIS_ID` = '$IPPIS_ID', `Full_Name` = '$Full_Name'  WHERE `id` = '$user_id'") or die(mysqli_error());

		header("location: index.php");
	}
?>
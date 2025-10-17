<?php
	require_once 'conn.php';
	include 'includes/session.php';
	if(ISSET($_POST['update'])){
		$user_id = $_POST['id'];
		$study_centre_email = $_POST['study_centre_email'];
		$director = $_POST['director'];
        $phone_number = $_POST['phone_number'];
        $sql = "UPDATE `study_centers` SET  `study_centre_email` = '$study_centre_email' , `director` = '$director', `phone_number` = '$phone_number' WHERE `id` = '$user_id'"; 
        if($conn->query($sql)){
		$_SESSION['success'] = 'Centre Record updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
		else{
		$_SESSION['error'] = 'Select Centre to edit first';
	}
	//header('location:staff2.php');
	echo "<script>window.location.href='centres.php';</script>";
    exit;
?>





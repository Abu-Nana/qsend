<?php
	require_once 'conn.php';
	include 'includes/session.php';
	if(ISSET($_POST['truncate'])){
        $sql = "DELETE FROM `study_centers`"; 
        if($conn->query($sql)){
		$_SESSION['success'] = 'Centre Record Deleted successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
		
	//header('location:staff2.php');
	echo "<script>window.location.href='reg-data.php';</script>";
    exit;
?>





<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$empid = $_POST['id'];
		$name = $_POST['name'];
		$title = $_POST['title'];
		$bankname = $_POST['bank_name'];
		$accountno = $_POST['account_no'];
		$pension_admin= $_POST['pension_admin'];
		$pensionpin = $_POST['pension_pin'];
		$department = $_POST['department'];
		$location = $_POST['Location'];
        $email = $_POST['email'];
		
		$sql = "UPDATE staff_record SET Full_Name = '$name', Job_Title = '$title', Bank_Name = '$bankname', Account_Number = '$accountno', Pension_Administrator = '$pension_admin', Pension_PIN = '$pensionpin', Department = '$department', Location = '$location', email = '$email' WHERE id = '$empid'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Staff Record updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Select Staff to edit first';
	}

	header('location: staff.php');
?>
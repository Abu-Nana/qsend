<?php
	include 'includes/session.php';

	if(isset($_POST['add_staff'])){
		$id = $_POST['id'];
		$email = $_POST['email'];
		$ippis = $_POST['ippis'];
		$name = $_POST['name'];
		$bankname = $_POST['bankname'];
        $acno = $_POST['acno'];
        $designation = $_POST['designation'];
        $school = $_POST['school'];
        $department = $_POST['department'];
        $location = $_POST['location'];
        $unit = $_POST['unit'];
        $grade = $_POST['grade'];
        $step = $_POST['step'];
        $gender = $_POST['gender'];
        $pension_admin= $_POST['pension_admin'];
        $pin = $_POST['pin'];
        $tin = $_POST['tin'];
        $appointment_date = $_POST['appointment_date'];
        $birthdate = $_POST['birthdate'];
        $union = $_POST['union'];
        $taxstate = $_POST['taxstate'];
        $nhf = $_POST['nhf'];
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
		$sql = "INSERT INTO staff_record (id, email, IPPIS_ID,  Full_Name, Bank_Name, Account_Number, Job_Title, School, Department, Location, Unit, Grade, Grade_Step, gender, Pension_Administrator, Pension_PIN, TIN, date_of_appointment, date_of_birth, union_name, tax_state,nhf_number,photo) VALUES ('$id', '$email', '$ippis', '$name', '$bankname', '$acno', '$designation', '$school','$department', '$location', '$unit', '$grade', '$step', '$gender', '$pension_admin', '$pin', '$tin', '$appointment_date', '$birthdate', '$union', '$taxstate', '$nhf','$filename')";
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

	header('location: staff-view.php');
?>
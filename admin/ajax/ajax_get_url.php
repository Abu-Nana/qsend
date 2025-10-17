<?php
session_start();
$config = "../inc/pdo_connectdb.php";
require $config;
if($_POST['id1'])
{

	$id=$_POST['id1'];
	$condtnal ="";
	$query="SELECT *
		FROM students
		";
	switch ($_SESSION['u_cat']) {
		case 'CTRU';
		case 'ICTO':
		$condtnal="WHERE vMatricNo ='$id' AND cstudycenterid ='".$_SESSION['fac_id']."'";
			break;
		case 'MGMT';
		case 'DEA';
		$condtnal="WHERE vMatricNo ='$id'";
		break;
		case 'FACU':
		$condtnal="WHERE vMatricNo ='$id' AND cfacultyid ='".$_SESSION['fac_id']."'";
		case 'DEA';
		case 'DEAT':

			// code...
			break;
		default:
			// code...
			break;
	}
	$query.=$condtnal;

    $stmt=$conn->prepare($query,$pdo_attr);
    $stmt->execute();
		if ($stmt->rowCount()>0){
			 $_SESSION['Returnurl']=$id;
				echo "1";
		}else{
				echo "0";
		}
	$stmt->closeCursor();
}else {
		echo "0";
}
?>

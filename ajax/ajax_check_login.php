<?php
session_start();
require_once __DIR__ . '/../config/database.php';
$conn = Database::getPDO();
$ddate = date("Y-m-d");
$_SESSION['email']="";
$t_usr="";
$t_pwd="";
$_SESSION['usrid']= "";
$_SESSION['fac_id']= "";
$_SESSION['u_cat'] ="";
$_SESSION['pwd_reset']="";

$t_usr = $_POST['id_usr1'];
$t_pwd = $_POST['id_pwd1'];
$_SESSION['email']=$t_usr;
$query ="SELECT employeeid,CONCAT(Title,' ',lname,' ',fname) as vname, cfacultyid
		FROM employees
		WHERE emailid =:username AND passwords=:password";
$pwd= md5($t_pwd);



$query ="SELECT usr_name,acct_name,usr_cat,usr_passwords,unit_code,pwd_reset
FROM usr_acct
WHERE usr_name ='$t_usr' AND usr_passwords='$pwd'";

// echo $query;
//exit;
$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$stmt->execute();
if ($stmt->rowCount()<=0){
	$stmt->closeCursor();
	echo "0";
	exit;
}
$_SESSION['email']=$t_usr;
while ($rw = $stmt->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT))
{
	$data="";
  $_SESSION['usrid']= $rw[0];
  $_SESSION['name'] = $rw[1];
  $_SESSION['u_cat'] = $rw[2];
  $_SESSION['pwd_reset']=$rw[5];
	// $_SESSION['role_id']= $rw[4];

	switch ($_SESSION['u_cat']) {
		case 'ADMIN':
		$_SESSION['role'] ="System Administrator";
			$data="1";
			break;
		case 'DEAN':
		case 'FAEO':
		$_SESSION['role'] ="Faculty User";
		$_SESSION['fac_id']= $rw[4];
			$data="2";
			break;
	case 'FHOD';
	case 'FDEO':
	$_SESSION['role'] ="Department User";
	$_SESSION['dept_id']= $rw[4];
	  $data="3";
			break;
 	case 'TECHO':
	case 'HDSS':
	$_SESSION['role'] ="Helpdesk/Support";
	 $data="4";
		break;
	case 'FPCT':
	$_SESSION['role'] ="Program User";
	$_SESSION['prog_id']= $rw[4];
		$data="5";
		break;
	case 'SPGS':
		$data="6";
		break;
	case 'MGMT':
	$_SESSION['role'] ="Management User";
		$data="7";
		break;
	}
}
if ($_SESSION['pwd_reset']==1){
	$data="9";
	// break;
}
echo $data;
$stmt->closeCursor();
 ?>

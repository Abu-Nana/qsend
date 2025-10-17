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



$query ="SELECT username, firstname, lastname, type, password, id
FROM admin
WHERE username ='$t_usr' AND password='$pwd'";

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
  $_SESSION['usrid']= $rw[5]; // id
  $_SESSION['username']= $rw[0]; // username
  $_SESSION['name'] = $rw[1] . ' ' . $rw[2]; // firstname + lastname
  $_SESSION['u_cat'] = $rw[3]; // type
  $_SESSION['admin'] = $rw[5]; // id for admin session

	// Map type to appropriate redirect
	$user_type = intval($rw[3]);
	switch ($user_type) {
		case 1: // Administrator
			$_SESSION['role'] = "System Administrator";
			$data = "3"; // Management dashboard
			break;
		case 2: // Regular user
			$_SESSION['role'] = "Faculty User";
			$data = "2"; // Search
			break;
		default:
			$_SESSION['role'] = "User";
			$data = "1"; // Faculty dashboard
			break;
	}
}
echo $data;
$stmt->closeCursor();
 ?>

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



$query ="SELECT id, username, firstname, lastname, cat, password
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
  $_SESSION['usrid']= $rw[0]; // id
  $_SESSION['admin'] = $rw[0]; // id for admin session
  $_SESSION['username']= $rw[1]; // username
  $_SESSION['name'] = $rw[2] . ' ' . $rw[3]; // firstname + lastname
  $_SESSION['u_cat'] = $rw[4]; // cat

	// Check if user is DEA or regular user based on 'cat' column
	$cat = strtolower(trim($rw[4]));
	if ($cat == 'dea') {
		// DEA user - management dashboard
		$_SESSION['role'] = "System Administrator";
		$data = "3"; // mgt_dashboard
	} else {
		// Regular user - search page
		$_SESSION['role'] = "Faculty User";
		$data = "2"; // search
	}
}
echo $data;
$stmt->closeCursor();
 ?>

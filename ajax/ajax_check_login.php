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

// First, get the user record to check password
$query ="SELECT id, username, firstname, lastname, cat, password
FROM admin
WHERE username ='$t_usr'";

$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute();

if ($stmt->rowCount()<=0){
	$stmt->closeCursor();
	echo "0";
	exit;
}

// Get user record
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Verify password - handle both MD5 (legacy) and bcrypt (new)
$password_valid = false;
$stored_password = $user['password'];

// Check if it's a bcrypt hash (starts with $2y$ or $2a$)
if (substr($stored_password, 0, 4) === '$2y$' || substr($stored_password, 0, 4) === '$2a$') {
	// Bcrypt password
	$password_valid = password_verify($t_pwd, $stored_password);
} else {
	// MD5 password (legacy)
	$password_valid = ($stored_password === md5($t_pwd));
}

if (!$password_valid) {
	echo "0";
	exit;
}
// Password is valid, set session variables
$_SESSION['email']=$t_usr;
$_SESSION['usrid']= $user['id'];
$_SESSION['admin'] = $user['id'];
$_SESSION['username']= $user['username'];
$_SESSION['name'] = $user['firstname'] . ' ' . $user['lastname'];
$_SESSION['u_cat'] = $user['cat'];

// Check if user is DEA or regular user based on 'cat' column
$cat = strtolower(trim($user['cat']));
if ($cat == 'dea') {
	// DEA user - management dashboard
	$_SESSION['role'] = "System Administrator";
	$data = "3"; // mgt_dashboard
} else {
	// Regular user - search page
	$_SESSION['role'] = "Faculty User";
	$data = "2"; // search
}
echo $data;
?>

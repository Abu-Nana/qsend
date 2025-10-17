<?php
session_start();
$configfile = "../inc/pdo_trans_connectdb.php";
require $configfile;
include '../inc/pdo_connectdb.php';
// $id = $_POST['id1'];
$c_pwd =$_POST['c_pwd1'];
$n_pwd =$_POST['n_pwd1'];
$cn_pwd =$_POST['cn_pwd1'];
try
	{
$tcon->beginTransaction();
$flag = true;

$query ="SELECT *
		FROM usr_acct
		WHERE usr_name ='".$_SESSION['usrid']."' AND usr_passwords = '".md5($c_pwd)."'";
$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute();
if($stmt->rowCount()<=0){
	$stmt->closeCursor();
	echo "2";
	exit;
}else{

}
$query ="UPDATE usr_acct
	SET usr_passwords ='".md5($n_pwd)."',pwd_reset=0
	WHERE usr_name ='".$_SESSION['usrid']."'";
	// AND usr_passwords = '".md5($c_pwd)."'";";

	$stmt = $tcon->exec($query);
	$tcon->commit();
		echo "1";
		exit;
	}catch(Exception $e){
		$tcon->rollBack();
		echo $e;
}
?>

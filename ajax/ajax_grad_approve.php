<?php
session_start();
date_default_timezone_set('Africa/Lagos');
$configfile = "../inc/pdo_trans_connectdb.php";
require $configfile;
$id = $_POST['id1'];
$ddate = date('yyyy-MM-dd');
$usr_name=$_POST['id1'];
$approve=0;
try
	{
		$tcon->beginTransaction();
		$flag = true;

$query2 ="UPDATE students
		SET grad_approved =1
		WHERE vMatricNo = '$id'";

		$query =  "INSERT INTO approve_grad_logs(approve_by,student,approve_remove)
          VALUES('".$_SESSION['usrid']."','$id','$approve')";
	
$stmt = $tcon->exec($query);
$stmt = $tcon->exec($query2);
$tcon->commit();
		echo "1";
		exit;
	}
    
    catch(Exception $e){
		$tcon->commit();
		echo $e;
}
?>

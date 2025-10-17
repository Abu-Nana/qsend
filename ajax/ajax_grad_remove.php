<?php
session_start();
$configfile = "../inc/pdo_trans_connectdb.php";
require $configfile;
$id = $_POST['id1'];
$approve=1;

try
	{
		$tcon->beginTransaction();
		$flag = true;


	$query2 ="UPDATE students
		SET grad_approved =0
		WHERE vMatricNo = '$id'";
		$query =  "INSERT INTO approve_grad_logs(approve_by,student,approve_remove)
          VALUES('".$_SESSION['usrid']."','$id','$approve')";

$stmt = $tcon->exec($query);
$stmt = $tcon->exec($query2);
$tcon->commit();
		echo "1";
		exit;
	}catch(Exception $e){
		$tcon->rollBack();
		echo $e;
}
?>

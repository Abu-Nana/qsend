<?php
session_start();
$configfile = "../inc/pdo_trans_connectdb.php";
require $configfile;
include '../inc/pdo_connectdb.php';
$id = $_POST['id1'];

try
	{
		$tcon->beginTransaction();
		$flag = true;

// $query ="SELECT *
// 		FROM usr_acct
// 		WHERE usr_name ='$id'";
// $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
// $stmt->execute();
// if($stmt->rowCount()>0){
// 	$stmt->execute();
// 	echo "0";
// 	exit;
// }

$query ="DELETE
	FROM usr_acct
	WHERE usr_name ='$id'";

	$stmt = $tcon->exec($query);

$query ="DELETE
	FROM usr_assess
	WHERE usr_name ='$id'";

	$stmt = $tcon->exec($query);

	$tcon->commit();
		echo "1";
		exit;
	}catch(Exception $e){
		$tcon->rollBack();
		echo $e;
}
?>

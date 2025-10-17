<?php
session_start();
$configfile = "../inc/pdo_trans_connectdb.php";
require $configfile;
include '../inc/pdo_connectdb.php';
$id = $_POST['id1'];
$row_pwd=$_POST['usr_pwd1'];
$msg="";
try
	{
		$tcon->beginTransaction();
		$flag = true;

$query ="SELECT usr_mob
		FROM usr_acct
		WHERE usr_name ='$id'";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$stmt->execute();
	if($stmt->rowCount()<=0){
		$stmt->execute();
		echo "0";
		exit;
	}
	while ($rw = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)){
		$usr_mob=$rw[0];
	}
	$stmt->closeCursor();
	$msg = "Your ERP account password has been reset. \n Your new  password is: ".$row_pwd;

	$query ="UPDATE usr_acct
	SET usr_passwords ='".md5($row_pwd)."',
	pwd_reset=1,pwd='$row_pwd'
	WHERE usr_name ='$id'";

	$stmt = $tcon->exec($query);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://mps.digitalpulseapi.net/1.0/send-sms/anq',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
		"sender": "NOUN",
		"message": "'.$msg.'",
		"receiver": "'.$usr_mob.'"}',
	CURLOPT_HTTPHEADER => array(
		'api-key: N1Y8NIuMPhV5kDwCQgBxEA==',
		'Content-Type: application/json'
	),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	$tcon->commit();
		echo "1";
		exit;
	}catch(Exception $e){
		$tcon->rollBack();
		echo $e;
}
?>

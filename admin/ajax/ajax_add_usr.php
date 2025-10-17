<?php
session_start();
include "../inc/pdo_connectdb.php";
include "../inc/pdo_trans_connectdb.php";
date_default_timezone_set('Africa/Lagos');

$ddate = date('Y-m-d');

$usr_name=$_POST['usr_name1'];
$acct_name=$_POST['acct_name1'];
$usr_mob=$_POST['usr_mob1'];
$usr_cat=$_POST['usr_cat1'];
// $d_date=$_POST['d_date1'];
$row_pwd =($_POST['usr_pwd1']);
$usr_pwd= md5($_POST['usr_pwd1']);
$usr_unit =$_POST['usr_unit1'];

$query ="SELECT *
		FROM usr_acct
		WHERE usr_name ='$usr_name'";

    //tt@noun.edu.ng
$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute();
if($stmt->rowCount()>0){
	$stmt->closeCursor();
	echo "2";
	exit;
}else{
  try {
  $tcon->beginTransaction();
  $query =  "INSERT INTO usr_acct(usr_name,usr_mob,d_date,acct_name,usr_cat,usr_passwords,pwd_reset,pwd,unit_code)
          VALUES('$usr_name','$usr_mob','$ddate','$acct_name','$usr_cat','$usr_pwd',1,'$row_pwd','$usr_unit')";
  $stmt = $tcon->exec($query);


  $from ="sonuh@noun.edu.ng";
  $msg = "Your user account is ". $usr_name ." \nYour default password is: ".$row_pwd;
  // use wordwrap() if lines are longer than 70 characters
  $msg = wordwrap($msg,70);

  $headers = 'From: '.$from."\r\n".
      'Reply-To: '.$from."\r\n" .
      'X-Mailer: PHP/' . phpversion();
  // send email
// if (mail("simeononuh@gmail.com","NOUN ERP Login Parameter",$msg,$headers)){
  // echo "send email successful";

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
  echo "$e";//"$query";
}
}


?>

$query =  "INSERT INTO usr_acct(usr_name,usr_mob,d_date,acct_name,usr_cat,usr_passwords,pwd_reset,pwd,unit_code)
          VALUES('$usr_name','$usr_mob','$ddate','$acct_name','$usr_cat','$usr_pwd',1,'$row_pwd','$usr_unit')";
  $stmt = $tcon->exec($query);



  <?php
session_start();
date_default_timezone_set('Africa/Lagos');
$configfile = "../inc/pdo_trans_connectdb.php";
require $configfile;
$id = $_POST['id1'];
$ddate = date('Y-m-d');

try
	{
		$tcon->beginTransaction();
		$flag = true;


	$query ="UPDATE students
		SET grad_approved =1
		WHERE vMatricNo = '$id'";

$stmt = $tcon->exec($query);
$tcon->commit();
		echo "1";
		exit;
	}
    $usr_name =$_SESSION['usrid']
    $query2 =  "INSERT INTO approve_grad_logs(approve_by,approve_on,student)
          VALUES('$usr_name','$usr_mob','$ddate','$id')";
  $stmt = $tcon->exec($query2);
    catch(Exception $e){
		$tcon->commit();
		echo $e;
}
?>

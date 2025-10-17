<?php
session_start();
$id =$_POST['id'];
$_SESSION['matric_id']=$id;
echo "1";
// location.reload();
 ?>

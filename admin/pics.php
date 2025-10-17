<?php
 $connection = require 'connection.php';
$res = mysqli_query("SELECT * FROM photos");
// $query_data = mysqli_query($connection,$res);

while($row = mysqli_fetch_assoc($res)){
  $name = $row['rrr'];
  $cust_id = $row['matno'];

  if(file_exists('pics/'.$name)){
    rename('pics/'.$name,'pics/'.$cust_id.".jpg");
  }
}
?>

<br>

<a href="sentitems.php">Update succesfully</a>
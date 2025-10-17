<?php
$connection = require 'connection.php';
$script = "select * from files";
$query_data = mysqli_query($connection,$script);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		table, th, td {
		  border: 1px solid;
		}
		table{
			  border-collapse: collapse;
			  width: 100%;
		}
	</style>
</head>
<body>
<table>
	<tr>
		<th>Study Center</th>
		<th>Student Center Name</th>
		<th>Exam Session</th>
		<th>Exam Day</th>
		<th>Password</th>
		<th>Date Created</th>
		<th>Action</th>
	</tr>
	<?php 
		if(mysqli_num_rows($query_data) > 0)  {
			while($obj = mysqli_fetch_assoc($query_data)){
				?>
			<tr>
				<td> <?php  echo $obj['study_center']; ?> </td>
				<td> <?php  echo $obj['student_center_name']; ?> </td>
				<td> <?php  echo $obj['exam_session']; ?> </td>
				<td> <?php  echo $obj['exam_day']; ?> </td>
				<td> <?php  echo $obj['password']; ?> </td>
				<td> <?php  echo date('D F, Y h:s a', strtotime($obj['created_at'])); ?> </td>
				<td> <a href="<?php echo $obj['file_name']; ?>">download</a> </td>
			</tr>
		<?php
			}
		}
		?>
</table>
</table>
</body>
</html>
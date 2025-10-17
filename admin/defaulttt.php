<?php $connection = require "connection.php"; 
$query = "select student_registrations.exam_session from `student_registrations` group by `student_registrations`.`exam_session`";
$query_data = mysqli_query($connection,$query);
$day = "select student_registrations.id,student_registrations.exam_day from `student_registrations` group by `student_registrations`.`exam_day` order by `id` asc";
$day_data = mysqli_query($connection,$day);

?>
<!DOCTYPE html>
<html>
<head>
	<title>PDF</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<style type="text/css">
	.form-control{
		margin-bottom:12px;
	}
</style>
</head>
<body>
	<div class="container">
		<div class="col-md-12">
	<?php
        	if(mysqli_num_rows($query_data) == 0)  {
              echo "no data found or invalid database connection";
        	}
	 ?>
			<form method="POST" action="pdf.php">
			 <div class="col-md-6">
		 	<label> Select Exam Session</label>
			 <select class="form-control" name="session">
			  	<?php if(mysqli_num_rows($query_data) > 0)  {
					while($obj = mysqli_fetch_assoc($query_data)){ 
					    echo '<option value="'. $obj['exam_session'] . '">'. $obj['exam_session'] . '</option>';
					}
				}
			    ?>
			  </select>
			</div>
			<div class="col-md-6">
		 	<label> Select Exam Day</label>
			  <select class="form-control" name="day">
			  	<?php if(mysqli_num_rows($day_data) > 0)  {
					while($obj = mysqli_fetch_assoc($day_data)){ 
					    echo '<option value="'. $obj['exam_day'] . '">'. $obj['exam_day'] . '</option>';
					}
				}
			    ?>
			  </select>
			</div>
			<div class="col-md-6">
			 	<label> Enter Email Subject</label>
			  <input class="form-control" type="text" name="subject">
			</div>
			<div class="col-md-6">
		 	<label> Enter Email Body</label>
			  <textarea class="form-control" name="body"></textarea>
			   <button class="btn btn-primary" type="submit" style="float: right">send</button>
			</div>
			 
			</form>
		</div>
	</div>
</body>
</html>
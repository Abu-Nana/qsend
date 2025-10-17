<?php 
    $connection = require 'connection.php';
    $script = "select 
        student_registrations.*,study_centers.study_centre_email,study_centers.phone_number,
        security_codes.security_code,security_codes.student_registration_id ,security_codes.id as code_id
        from `security_codes` 
        inner join `student_registrations` on `student_registrations`.`id` = `security_codes`.`student_registration_id`
        inner join `study_centers` on `study_centers`.`study_center_code` = `student_registrations`.`study_center_code`
        ORDER By `security_codes`.`id` DESC";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div style="margin: 102px 100px">
        <table id="security-table" class="display">
            <thead>
                <tr>
                    <th>Study Center</th>
                    <th>Student Center Code</th>
                    <th>Center Email Address</th>
                    <th>Center Phone</th>
                    <th>Exam Session</th>
                    <th>Exam Day</th>
                    <th>Security Code</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                if(mysqli_num_rows($query_data) > 0)  {
                    while($obj = mysqli_fetch_assoc($query_data)){
                        ?>
                    <tr>
                        <td> <?php  echo $obj['study_center']; ?> </td>
                        <td> <?php  echo $obj['study_center_code']; ?> </td>
                        <td> <?php  echo $obj['study_centre_email']; ?> </td>
                        <td> <?php  echo $obj['phone_number']; ?> </td>
                        <td> <?php  echo $obj['exam_session']; ?> </td>
                        <td> <?php  echo $obj['exam_day']; ?> </td>
                        <td> <?php  echo $obj['security_code']; ?> </td>
                    </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
      $('#security-table').DataTable();
    });
</script>
</body>
</html>
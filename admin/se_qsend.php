<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Send POP Exam Questions Mondays
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Questions</li>
        <li class="active">Scheduled Examination Questions Mondays</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
          <?php $connection = require "connection.php"; 
$query = "select student_registrations.exam_session from `student_registrations` group by `student_registrations`.`exam_session`";
$query_data = mysqli_query($connection,$query);

$day = "select student_registrations.id,student_registrations.exam_day from `student_registrations` group by `student_registrations`.`exam_day` order by `id` asc";
$day_data = mysqli_query($connection,$day);
?>
	<?php
        	if(mysqli_num_rows($query_data) == 0)  {
              echo "no data found or invalid database connection";
        	}
	 ?>
			<form method="POST" action="pdf_with_security.php">
			<div class="form-group">
			 <div class="col-md-6">
		 	<label> Select Exam Session</label>
			 <select class="form-control" name="exam_session">
			  	<?php if(mysqli_num_rows($query_data) > 0)  {
					while($obj = mysqli_fetch_assoc($query_data)){ 
					    echo '<option value="'. $obj['exam_session'] . '">'. $obj['exam_session'] . '</option>';
					}
				}
			    ?>
			  </select>
			</div>
			</div>
			<div class="form-group">
			<div class="col-md-6">
		 	<label> Select Exam Day</label>
			  <select class="form-control" name="exam_day">
			  	<?php if(mysqli_num_rows($day_data) > 0)  {
					while($obj = mysqli_fetch_assoc($day_data)){ 
					    echo '<option value="'. $obj['exam_day'] . '">'. $obj['exam_day'] . '</option>';
					}
				}
			    ?>
			  </select>
			</div>
			</div>
			<div class="form-group">
			<div class="col-md-6">
			 	<label> Enter Email Subject</label>
			  <input class="form-control" type="text" name="subject">
			</div>
			</div>
			<div class="form-group">
			<div class="col-md-6">
		 	<label> Enter Email Body</label>
			  <textarea class="form-control" name="body"></textarea>
			  
			</div>
            
			 <button class="btn btn-primary" type="submit" style="float: right">Send Questions</button>
			 </div>
			</form>
		</div>
	</div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>

</div>
<?php include 'includes/scripts.php'; ?>

<?php include 'includes/datatable_initializer.php'; ?>
echo "
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
<script>
$(document).ready(function(){
  $('#statusModal').modal('show');
});
</script>
";
</body>
</html>


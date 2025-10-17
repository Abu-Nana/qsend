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
        Regsitration Data
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Registrations</li>
        <li class="active">Reg Data Details List</li>
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
           <!-- <a href="truncate.php" name="truncate1" data-toggle="modal" class="fa fa-trash">Truncate Reg Data Table</a>-->
		<!--	<button name="truncate" action="truncate.php" class="btn btn-danger"><span class="glyphicon glyphicon-edit"></span> Truncate Reg Data Table</button>-->
             <form name="hak_form10" action="#" method="post">
            <button type="submit" name="hak_form10" class="btn btn-danger"><span class="glyphicon glyphicon-edit"></span> Truncate Reg Data Table</button>
            </form>
            </div>
            
            <div class="box-body">
             <table id="example1" class="table table-bordered">
                <thead>
                    
                  <th> Matric Number</th>
                  <th>Study Centre </th>
                   <th>Centre Code</th>
                  <th>Registered Course</th>
                    <th>Exam Day</th>
                     <th>Exam Session</th>
                      <th>Exam Date</th>
                </thead>
                <tbody>
                     
                  <?php
                   
				//	require 'conn.php';
                        $sql="SELECT *, student_registrations.id AS id FROM student_registrations";
                        $query = $conn->query($sql);
                         while($fetch = $query->fetch_assoc())
					{
				?>
                        <tr>
                            
                            <td><?php echo $fetch['matric_number'];?></td>
                            <td><?php echo $fetch['study_center'];?></td>
                            <td><?php echo $fetch['study_center_code'];?></td>
                            <td><?php echo $fetch['course'];?></td>
                            <td><?php echo $fetch['exam_day'];?></td>
                            <td><?php echo $fetch['exam_session'];?></td>
                            <td><?php echo $fetch['exa_date'];?></td>
                            
                       <!--   <td><button class="btn btn-warning" data-toggle="modal" type="button" data-target="#update_modal<?php echo $fetch['id'];?>"><span class="glyphicon glyphicon-edit"></span> Edit Centre Details</button></td>
                                     
                           -->
                            
                        
                        </tr>

                      <?php
					
					
                        
					
					}
				?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    <?php
include "dbconfig.php";
$query2 = mysqli_query($dbconnection,"SELECT *, student_registrations.id AS id FROM student_registrations");
if (isset($_POST['hak_form10'])){
    
        $query = mysqli_query($dbconnection,"delete from student_registrations");
    }
       

?>
  <?php include 'includes/footer.php'; ?>

</div>
<?php include 'includes/scripts.php'; ?>

<?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>


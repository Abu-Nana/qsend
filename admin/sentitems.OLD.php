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
        Study Centre List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Centres</li>
        <li class="active">Full Study Centres List</li>
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
           <!--    <a href="#add_staff" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add New Staff</a>-->
            </div>
            <div class="box-body">
             <table id="example1" class="table table-bordered">
                <thead>
                    
                  <th> Centre Code</th>
                  <th>Centre Name</th>
                   <th>Exam Session</th>
                  <th>Exam Day</th>
                    <th>Password</th>
					<th>Date Examined</th>
          <th>Sent By</th>
          <th>Sender Address</th>        
                  <th>Action</th>
                </thead>
                <tbody>
                     
                  <?php
                   
				//	require 'conn.php';
                        $sql="SELECT *, files.id AS id FROM files where sem=251";
                        $query = $conn->query($sql);
                         while($fetch = $query->fetch_assoc())
					{
				?>
                        <tr>
                            
                            <td><?php echo $fetch['student_center_name'];?></td>
                            <td><?php echo $fetch['study_center'];?></td>
                            <td><?php echo $fetch['exam_session'];?></td>
                            <td><?php echo $fetch['exam_day'];?></td>
                            <td><?php echo $fetch['password'];?></td>
                            <td> <?php  echo date('dS F Y h:s a', strtotime($fetch['created_at'])); ?> </td>
                            <td><?php echo $fetch['sentby'];?></td>
                            <td><?php echo $fetch['ip_address'];?></td>
                          	<td> <a href="<?php echo $fetch['file_name']; ?>">Download</a> </td>
                                     
                           
                            
                        
                        </tr>
                      <?php
					
					include 'update_user.php';
                         
					
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
    
  <?php include 'includes/footer.php'; ?>
 
</div>
<?php include 'includes/scripts.php'; ?>

<?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>
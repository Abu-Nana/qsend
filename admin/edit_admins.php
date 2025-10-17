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
        System Administrators
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Staff</li>
        <li class="active">System Administrators</li>
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
               
            </div>
            <div class="box-body">
             <table id="example1" class="table table-bordered">
                <thead>
                    <th>ID</th>
                  <th>Username</th>
                    <th>Firstname</th>
                  <th>Last Name</th>
                  <th>Date Created</th>
                    
                  <th>Edit</th>
                  
                </thead>
                <tbody>
                     
                  <?php
                   
				//	require 'conn.php';
                        $sql="SELECT *, admin.id AS id FROM admin";
                        $query = $conn->query($sql);
                         while($fetch = $query->fetch_assoc())
					{
				?>
                        <tr>
                            
                            <td><?php echo $fetch['id'];?></td>
                            <td><?php echo $fetch['username'];?></td>
                            <td><?php echo $fetch['firstname'];?></td>
                            <td><?php echo $fetch['lastname'];?></td>
                            <td><?php echo $fetch['created_on'];?></td>
                            
                            
                          <td><button class="btn btn-warning" data-toggle="modal" type="button" data-target="#update_modal<?php echo $fetch['id'];?>"><span class="glyphicon glyphicon-edit"></span> Edit Details</button></td>
                             
                                     
                           
                            
                        
                        </tr>
                      <?php
					
					include 'update_admin.php';
                         
					
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
  <?php include 'includes/admin_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>


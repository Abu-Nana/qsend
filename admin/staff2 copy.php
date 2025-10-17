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
        Full Staff List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Staff</li>
        <li class="active">Full Staff List</li>
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
               <a href="#add_staff" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add New Staff</a>
            </div>
            <div class="box-body">
             <table id="example1" class="table table-bordered">
                <thead>
                    
                  <th>Staff ID</th>
                    <th>Email Address</th>
                  <th>Name</th>
                  <th>Designation</th>
                    <th>Department</th>
                    <th>Bank Name</th>
                  <th>Account Number</th>
                  <th>Pension Administrator</th>
                     <th>Pension PIN</th>
                     <th>Tax state</th>
                    <th>NHF Number</th>
                  <th>Edit</th>
                </thead>
                <tbody>
                     
                  <?php
                   
				//	require 'conn.php';
                        $sql="SELECT *, staff_record.id AS id FROM staff_record";
                        $query = $conn->query($sql);
                         while($fetch = $query->fetch_assoc())
					{
				?>
                        <tr>
                            
                            <td><?php echo $fetch['id'];?></td>
                            <td><?php echo $fetch['email'];?></td>
                            <td><?php echo $fetch['Full_Name'];?></td>
                            <td><?php echo $fetch['Job_Title'];?></td>
                            <td><?php echo $fetch['Department'];?></td>
                            <td><?php echo $fetch['Bank_Name'];?></td>
                            <td><?php echo $fetch['Account_Number'];?></td>
                            <td><?php echo $fetch['Pension_Administrator'];?></td>
                            <td><?php echo $fetch['Pension_PIN'];?></td>
                            <td><?php echo $fetch['tax_state'];?></td>
                            <td><?php echo $fetch['nhf_number'];?></td>
                            
                          <td><button class="btn btn-warning" data-toggle="modal" type="button" data-target="#update_modal<?php echo $fetch['id'];?>"><span class="glyphicon glyphicon-edit"></span> Edit Staff Details</button></td>
                                     
                           
                            
                        
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
  <?php include 'includes/staff_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>


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
        Add New Study Centre Record
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Staff</li>
        <li class="active">Add New Centre</li>
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
            
            <div class="box-body">
             <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Study Centre</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="admin_add.php" enctype="multipart/form-data">
          		  <div class="form-group">
                  	<label for="firstname" class="col-sm-3 control-label">Study Centre Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="centre" name="centre" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="firstname" class="col-sm-3 control-label">Centre Director</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="director" name="director" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="lastname" class="col-sm-3 control-label">Centre Code</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="code" name="code" required>
                  	</div>
                </div>
                <div class="form-group">
                  	<label for="username" class="col-sm-3 control-label">Centre Email</label>

                  	<div class="col-sm-9">
                    	<input type="email" class="form-control" id="email" name="email" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="password" class="col-sm-3 control-label">Phone Number</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="mobilenumber" name="mobilenumber" required>
                  	</div>
                </div>
                     
          	</div>
          	<div class="modal-footer">
            	
            	<button type="submit" class="btn btn-primary btn-flat" name="add_admin"><i class="fa fa-save"></i> Add New Centre</button>
            	</form>
          	</div>
        </div>
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


</body>
</html>


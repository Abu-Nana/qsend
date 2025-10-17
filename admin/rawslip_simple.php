<?php 
include 'includes/session.php'; 
include 'includes/header.php'; 
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-upload"></i> Registration Data Upload (Simple Mode)
      </h1>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Upload CSV File</h3>
            </div>
            <div class="box-body">
              <form action="importData.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Select CSV File:</label>
                  <input type="file" name="file" accept=".csv" required class="form-control">
                  <small class="help-block">Only CSV files are allowed</small>
                </div>
                <div class="form-group">
                  <button type="submit" name="importSubmit" class="btn btn-success">
                    <i class="fa fa-upload"></i> Upload and Import
                  </button>
                  <a href="templates/IMPORT.csv" class="btn btn-primary" download>
                    <i class="fa fa-download"></i> Download Template
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Instructions</h3>
            </div>
            <div class="box-body">
              <ol>
                <li>Download the CSV template</li>
                <li>Fill in your data</li>
                <li>Upload the CSV file</li>
                <li>Click "Upload and Import"</li>
              </ol>
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


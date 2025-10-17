<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
    <?php
include_once 'dbConfig.php';

// Get status message
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Monthly Payslip data has been uploaded successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
    ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Upload Monthly Pay Slip Data
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Monthly Payslip Upload</li>
      </ol>
    </section>
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
        <!-- Display status message -->
<?php if(!empty($statusMsg)){ ?>
<div class="col-xs-12">
    <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>
        <?php
        $con = mysqli_connect("localhost","root","","2022");
      
    // mysqli_connect("servername","username","password","database_name")
   
    // Get all the categories from category table
    $sql = "SELECT * FROM `payslip`";
    $all_categories = mysqli_query($con,$sql);
   
    // The following code checks if the submit button is clicked 
    // and inserts the data in the database accordingly
    if(isset($_POST['submit']))
    {
        // Store the Product name in a "name" variable
        $name = mysqli_real_escape_string($con,$_POST['month']);
         
        // Store the Category ID in a "id" variable
        $id = mysqli_real_escape_string($con,$_POST['year']); 
         
        // Creating an insert query using SQL syntax and
        // storing it in a variable.
        $$sql_insert("delete from `payslip` where month = '".$name."' and  year = '".$id."', status = '".$status."', modified = NOW() WHERE email = '".$email."'");
         
        $sql_insert = 
        "delete from `payslip`(`product_name`, `category_id`)
            VALUES ('$name','$id')";
           
          // The following code attempts to execute the SQL query
          // if the query executes with no errors 
          // a javascript alert message is displayed
          // which says the data is inserted successfully
          if(mysqli_query($con,$sql_insert))
        {
            echo '<script>alert("Product added successfully")</script>';
        }
    }
?>
   
<div class="box-body">

    </div>
   

<!-- Show/hide CSV upload form -->
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
</script>
    
     <div class="box-body">
         <hr>
         <h1>
        Short Instruction
      </h1>
  <li>Prepared the CSV file in the following Format <a href="templates/IMPORT.csv" download>Download link</a></li>
  <li>Click Upload Monthly Payslip Button </li>
  <li>Browse the file location </li>
  <li>Click on Open</li>
    <li>Click on Upload Monthly Payslip Button</li>
</ul>
    </div
        </body>
</html>
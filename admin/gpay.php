<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard 3</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor1/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor1/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor1/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor1/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor1/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor1/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor1/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor1/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor1/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor1/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor1/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="js/datatables/datatables.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

    <script src="js/jquery-3.4.1.js"></script>
    <script src="https://ajax.googleapis.com//ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<?php
include "dbconfig.php";
$query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id");
if (isset($_POST['hak_form4'])){
    $month = $_POST['hak_month4'];
    $year = $_POST['hak_year4'];
    if ($month != '' && $year != ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id where ps.month = '$month' and ps.year = '$year'");
    }
    elseif ($month != '' && $year == ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id where ps.month = '$month'");
    }
    elseif ($month == '' && $year != ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id where ps.year = '$year'");
    }
    else{
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id");
    }
}
?>
    <?php
include "dbconfig.php";
$query = mysqli_query($dbconnection,"select sr.*, ps.Grade as grade, ps.Grade_Step as Grade_Step from payslip as sr INNER JOIN staff_record as ps ON sr.id = ps.id");
if (isset($_POST['hak_form11'])){
    $month = $_POST['hak_month11'];
    $year = $_POST['hak_year11'];
    if ($month != '' && $year != ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.Grade as grade, ps.Grade_Step as Grade_Step from payslip as sr INNER JOIN staff_record as ps ON sr.id = ps.id where ps.Grade = '$month' and ps.Grade_Step = '$year'");
    }
    elseif ($month != '' && $year == ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.Grade as grade, ps.Grade_Step as Grade_Step from payslip as sr INNER JOIN staff_record as ps ON sr.id = ps.id where ps.Grade = '$month'");
    }
    elseif ($month == '' && $year != ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.Grade as grade, ps.Grade_Step as Grade_Step from payslip as sr INNER JOIN staff_record as ps ON sr.id = ps.id where ps.Grade_Step = '$year'");
    }
    else{
        $query = mysqli_query($dbconnection,"select sr.*, ps.Grade as grade, ps.Grade_Step as Grade_Step from payslip as sr INNER JOIN staff_record as ps ON sr.id = ps.id");
    }
}
?>
    <?php
include "dbconfig.php";
$query2 = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from  payslip as ps");
if (isset($_POST['hak_form10'])){
    $month = $_POST['hak_month10'];
    $year = $_POST['hak_year10'];
    if ($month != '' && $year != ''){
        $query = mysqli_query($dbconnection,"delete from payslip  where month = '$month' and year = '$year'");
    }
       }

?>
<body class="hold-transition skin-blue sidebar-mini">
   

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
    
     <div class="content-wrapper">
         <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Generate/Send Montly Payslip
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>PaySlip Generation</li>
        <li class="active">PaySlip Generation</li>
      </ol>
   
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
<script>
    $(document).on('click','#selectAll',function(e) {
        var isChecked = $(this).prop("checked");
        if($(this).prop("checked") == true){
            $('.hak_cb').prop('checked', isChecked);
        }
        else if($(this).prop("checked") == false){
            isChecked = false;
            $('.hak_cb').prop('checked', isChecked);
        }
    });
</script>
<div class="page-wrapper">
    <!-- PAGE CONTENT-->
    <div class="main-content pt-3">
        <form name="hak_form4" action="#" method="post">
            <div class="row mb-5 ml-5">
                <div class="col-3">
                    <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_month4">
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_year4">
                            <option value="">Select Year</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <option value="2034">2034</option>
                            <option value="2035">2035</option>
                            <option value="2036">2036</option>
                            <option value="2037">2037</option>
                            <option value="2038">2038</option>
                            <option value="2039">2039</option>
                            <option value="2040">2040</option>
                            <option value="2041">2041</option>
                            <option value="2042">2042</option>
                            <option value="2043">2043</option>
                            <option value="2044">2044</option>
                            <option value="2045">2045</option>
                            <option value="2046">2046</option>
                            <option value="2047">2047</option>
                            <option value="2048">2048</option>
                            <option value="2049">2049</option>
                            <option value="2050">2050</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="hak_form4" class="btn btn-primary btn-lg">Filter Staff</button>
                </div>
            </div>
        </form>
        <form name="hak_form11" action="#" method="post">
            <div class="row mb-5 ml-5">
                <div class="col-3">
                    <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_month11">
                            <option value="">Select Grade Level</option>
                            <?php
                          $sql = "SELECT distinct Grade FROM payslip";
                          $query = $conn->query($sql);
                          while($prow = $query->fetch_assoc()){
                            echo "
                              <option value='".$prow['id']."'>".$prow['Grade']."</option>
                            ";
                          }
                        ?>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_year11">
                            <option value="">Select Grade Step</option>
                            <?php
                          $sql = "SELECT distinct Grade_Step FROM payslip";
                          $query = $conn->query($sql);
                          while($prow = $query->fetch_assoc()){
                            echo "
                              <option value='".$prow['id']."'>".$prow['Grade_Step']."</option>
                            ";
                          }
                        ?>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="hak_form11" class="btn btn-primary btn-lg">Filter Staff</button>
                </div>
            </div>
        </form>
        <form name="hak_form10" action="#" method="post">
            <div class="row mb-5 ml-5">
                <div class="col-3">
                    <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_month10">
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_year10">
                            <option value="">Select Year</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <option value="2034">2034</option>
                            <option value="2035">2035</option>
                            <option value="2036">2036</option>
                            <option value="2037">2037</option>
                            <option value="2038">2038</option>
                            <option value="2039">2039</option>
                            <option value="2040">2040</option>
                            <option value="2041">2041</option>
                            <option value="2042">2042</option>
                            <option value="2043">2043</option>
                            <option value="2044">2044</option>
                            <option value="2045">2045</option>
                            <option value="2046">2046</option>
                            <option value="2047">2047</option>
                            <option value="2048">2048</option>
                            <option value="2049">2049</option>
                            <option value="2050">2050</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="hak_form10" class="btn btn-primary btn-lg">Delete Wrong Entries</button>
                </div>
            </div>
        </form>
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <form method="post" action="actions.php" target="_blank">
                <div class="row">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning datatable" id="tblData">
                            <thead>
                            <tr>
                                <th><label class="au-checkbox"><input type="checkbox" id="selectAll"><span class="au-checkmark"></span></label></th>
                                <th>ID</th>
                                <th>Staff Name</th>
                                <th>Email</th>
                                <th>Month</th>
                                <th>Year</th>
<!--                                <th class="text-right">price</th>-->
<!--                                <th class="text-right">quantity</th>-->
<!--                                <th class="text-right">total</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($query))
                            {
                            ?>
                                <tr>
                                    <td><label class="au-checkbox"><input type="checkbox" name="hak_select[]" class="hak_cb" value="<?php echo $row['id'];?>"><span class="au-checkmark"></span></label></td>
                                    <td><?php echo $row['id'];?></td>
                                    <td><?php echo $row['Full_Name'];?></td>
                                    <td><?php echo $row['email'];?></td>
                                    <td><?php echo $row['month'];?></td>
                                    <td><?php echo $row['year'];?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="row">
                        <h4 class="text-danger col-md-12 mb-3">* Select Only when you change the month if you don't want to change the month so don't need to change it</h4>
                        <div class="col-3">
                            <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_month">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_year">
                                    <option value="">Select Year</option>
                            <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                    <option value="2032">2032</option>
                                    <option value="2033">2033</option>
                                    <option value="2034">2034</option>
                                    <option value="2035">2035</option>
                                    <option value="2036">2036</option>
                                    <option value="2037">2037</option>
                                    <option value="2038">2038</option>
                                    <option value="2039">2039</option>
                                    <option value="2040">2040</option>
                                    <option value="2041">2041</option>
                                    <option value="2042">2042</option>
                                    <option value="2043">2043</option>
                                    <option value="2044">2044</option>
                                    <option value="2045">2045</option>
                                    <option value="2046">2046</option>
                                    <option value="2047">2047</option>
                                    <option value="2048">2048</option>
                                    <option value="2049">2049</option>
                                    <option value="2050">2050</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form" class="btn btn-primary btn-lg"> Email Payslip</button>
                        </div>
                    </div>
                </form>
                <form name="hak_form2" action="actions2.php" target="_blank" method="post">
                    <div class="row  mt-5">
                        <div class="col-1">
                            <input class="form-control" name="hak_id2" placeholder="Enter ID">
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_month2">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_year2">
                                    <option value="">Select Year</option>
                            <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                    <option value="2032">2032</option>
                                    <option value="2033">2033</option>
                                    <option value="2034">2034</option>
                                    <option value="2035">2035</option>
                                    <option value="2036">2036</option>
                                    <option value="2037">2037</option>
                                    <option value="2038">2038</option>
                                    <option value="2039">2039</option>
                                    <option value="2040">2040</option>
                                    <option value="2041">2041</option>
                                    <option value="2042">2042</option>
                                    <option value="2043">2043</option>
                                    <option value="2044">2044</option>
                                    <option value="2045">2045</option>
                                    <option value="2046">2046</option>
                                    <option value="2047">2047</option>
                                    <option value="2048">2048</option>
                                    <option value="2049">2049</option>
                                    <option value="2050">2050</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form2" class="btn btn-primary btn-lg">Email PaySlip</button>
                        </div>
                    </div>
                </form>

                <form name="hak_form3" action="actions3.php" target="_blank" method="post">
                    <div class="row  mt-5">
                        <div class="col-1">
                            <input class="form-control" name="hak_id3" placeholder="Enter ID">
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_month3">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_year3">
                                    <option value="">Select Year</option>
                            <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                    <option value="2032">2032</option>
                                    <option value="2033">2033</option>
                                    <option value="2034">2034</option>
                                    <option value="2035">2035</option>
                                    <option value="2036">2036</option>
                                    <option value="2037">2037</option>
                                    <option value="2038">2038</option>
                                    <option value="2039">2039</option>
                                    <option value="2040">2040</option>
                                    <option value="2041">2041</option>
                                    <option value="2042">2042</option>
                                    <option value="2043">2043</option>
                                    <option value="2044">2044</option>
                                    <option value="2045">2045</option>
                                    <option value="2046">2046</option>
                                    <option value="2047">2047</option>
                                    <option value="2048">2048</option>
                                    <option value="2049">2049</option>
                                    <option value="2050">2050</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form3" class="btn btn-primary btn-lg">Generate  Payslip</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
 </section>
</div>
         
     <?php include 'includes/footer.php'; ?>
  
<?php include 'includes/scripts.php'; ?>
<!-- Jquery JS-->
<script src="vendor1/jquery-3.2.1.min.js"></script>
<!-- Bootstrap JS-->
<script src="vendor1/bootstrap-4.1/popper.min.js"></script>
<script src="vendor1/bootstrap-4.1/bootstrap.min.js"></script>
<!-- Vendor JS       -->
<script src="vendor1/slick/slick.min.js">
</script>
<script src="vendor1/wow/wow.min.js"></script>
<script src="vendor1/animsition/animsition.min.js"></script>
<script src="vendor1/bootstrap-progressbar/bootstrap-progressbar.min.js">
</script>
<script src="vendor1/counter-up/jquery.waypoints.min.js"></script>
<script src="vendor1/counter-up/jquery.counterup.min.js">
</script>
<script src="vendor1/circle-progress/circle-progress.min.js"></script>
<script src="vendor1/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="vendor1/chartjs/Chart.bundle.min.js"></script>
<script src="vendor1/select2/select2.min.js">
</script>

<!-- Main JS-->
<script src="js/main.js"></script>
<script src="js/datatables/jquery.dataTables.min.js"></script>
<script src="js/datatables/datatables.min.js"></script>
<script>
    $(function () {
        $('#tableId').DataTable();
        if ($('.datatable').length > 0) {
            $('.datatable').DataTable({
                "bFilter": false,
                "lengthMenu": [[20,50,100, 200, 300, -1], [20,50,100, 200, 300, "All"]],
                "filter": true,
                "searching": true,
            });
        }
    });
</script>

</body>

</html>
<!-- end document-->





<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard 3</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor1/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor1/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor1/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor1/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor1/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor1/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor1/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor1/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor1/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor1/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor1/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="js/datatables/datatables.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

    <script src="js/jquery-3.4.1.js"></script>
    <script src="https://ajax.googleapis.com//ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<?php
include "dbconfig.php";
$query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id");
if (isset($_POST['hak_form4'])){
    $month = $_POST['hak_month4'];
    $year = $_POST['hak_year4'];
    if ($month != '' && $year != ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id where ps.month = '$month' and ps.year = '$year'");
    }
    elseif ($month != '' && $year == ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id where ps.month = '$month'");
    }
    elseif ($month == '' && $year != ''){
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id where ps.year = '$year'");
    }
    else{
        $query = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from staff_record as sr INNER JOIN payslip as ps ON sr.id = ps.id");
    }
}
?>
    <?php
include "dbconfig.php";
$query2 = mysqli_query($dbconnection,"select sr.*, ps.month as month, ps.year as year from  payslip as ps");
if (isset($_POST['hak_form10'])){
    $month = $_POST['hak_month10'];
    $year = $_POST['hak_year10'];
    if ($month != '' && $year != ''){
        $query = mysqli_query($dbconnection,"delete from payslip  where month = '$month' and year = '$year'");
    }
       }

?>
<body class="hold-transition skin-blue sidebar-mini">
   

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
    
     <div class="content-wrapper">
         <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Generate/Send Montly Payslip
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>PaySlip Generation</li>
        <li class="active">PaySlip Generation</li>
      </ol>
   
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
<script>
    $(document).on('click','#selectAll',function(e) {
        var isChecked = $(this).prop("checked");
        if($(this).prop("checked") == true){
            $('.hak_cb').prop('checked', isChecked);
        }
        else if($(this).prop("checked") == false){
            isChecked = false;
            $('.hak_cb').prop('checked', isChecked);
        }
    });
</script>
<div class="page-wrapper">
    <!-- PAGE CONTENT-->
    <div class="main-content pt-3">
        <form name="hak_form4" action="#" method="post">
            <div class="row mb-5 ml-5">
                <div class="col-3">
                    <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_month4">
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_year4">
                            <option value="">Select Year</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <option value="2034">2034</option>
                            <option value="2035">2035</option>
                            <option value="2036">2036</option>
                            <option value="2037">2037</option>
                            <option value="2038">2038</option>
                            <option value="2039">2039</option>
                            <option value="2040">2040</option>
                            <option value="2041">2041</option>
                            <option value="2042">2042</option>
                            <option value="2043">2043</option>
                            <option value="2044">2044</option>
                            <option value="2045">2045</option>
                            <option value="2046">2046</option>
                            <option value="2047">2047</option>
                            <option value="2048">2048</option>
                            <option value="2049">2049</option>
                            <option value="2050">2050</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="hak_form4" class="btn btn-primary btn-lg">Filter Staff</button>
                </div>
            </div>
        </form>
        <form name="hak_form10" action="#" method="post">
            <div class="row mb-5 ml-5">
                <div class="col-3">
                    <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_month10">
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                        <select class="js-select2" name="hak_year10">
                            <option value="">Select Year</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <option value="2034">2034</option>
                            <option value="2035">2035</option>
                            <option value="2036">2036</option>
                            <option value="2037">2037</option>
                            <option value="2038">2038</option>
                            <option value="2039">2039</option>
                            <option value="2040">2040</option>
                            <option value="2041">2041</option>
                            <option value="2042">2042</option>
                            <option value="2043">2043</option>
                            <option value="2044">2044</option>
                            <option value="2045">2045</option>
                            <option value="2046">2046</option>
                            <option value="2047">2047</option>
                            <option value="2048">2048</option>
                            <option value="2049">2049</option>
                            <option value="2050">2050</option>
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="hak_form10" class="btn btn-primary btn-lg">Delete Wrong Entries</button>
                </div>
            </div>
        </form>
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <form method="post" action="actions.php" target="_blank">
                <div class="row">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning datatable" id="tblData">
                            <thead>
                            <tr>
                                <th><label class="au-checkbox"><input type="checkbox" id="selectAll"><span class="au-checkmark"></span></label></th>
                                <th>ID</th>
                                <th>Staff Name</th>
                                <th>Email</th>
                                <th>Month</th>
                                <th>Year</th>
<!--                                <th class="text-right">price</th>-->
<!--                                <th class="text-right">quantity</th>-->
<!--                                <th class="text-right">total</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($query))
                            {
                            ?>
                                <tr>
                                    <td><label class="au-checkbox"><input type="checkbox" name="hak_select[]" class="hak_cb" value="<?php echo $row['id'];?>"><span class="au-checkmark"></span></label></td>
                                    <td><?php echo $row['id'];?></td>
                                    <td><?php echo $row['Full_Name'];?></td>
                                    <td><?php echo $row['email'];?></td>
                                    <td><?php echo $row['month'];?></td>
                                    <td><?php echo $row['year'];?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="row">
                        <h4 class="text-danger col-md-12 mb-3">* Select Only when you change the month if you don't want to change the month so don't need to change it</h4>
                        <div class="col-3">
                            <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_month">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_year">
                                    <option value="">Select Year</option>
                            <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                    <option value="2032">2032</option>
                                    <option value="2033">2033</option>
                                    <option value="2034">2034</option>
                                    <option value="2035">2035</option>
                                    <option value="2036">2036</option>
                                    <option value="2037">2037</option>
                                    <option value="2038">2038</option>
                                    <option value="2039">2039</option>
                                    <option value="2040">2040</option>
                                    <option value="2041">2041</option>
                                    <option value="2042">2042</option>
                                    <option value="2043">2043</option>
                                    <option value="2044">2044</option>
                                    <option value="2045">2045</option>
                                    <option value="2046">2046</option>
                                    <option value="2047">2047</option>
                                    <option value="2048">2048</option>
                                    <option value="2049">2049</option>
                                    <option value="2050">2050</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form" class="btn btn-primary btn-lg"> Email Payslip</button>
                        </div>
                    </div>
                </form>
                <form name="hak_form2" action="actions2.php" target="_blank" method="post">
                    <div class="row  mt-5">
                        <div class="col-1">
                            <input class="form-control" name="hak_id2" placeholder="Enter ID">
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm w-100 mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_month2">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_year2">
                                    <option value="">Select Year</option>
                            <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                    <option value="2032">2032</option>
                                    <option value="2033">2033</option>
                                    <option value="2034">2034</option>
                                    <option value="2035">2035</option>
                                    <option value="2036">2036</option>
                                    <option value="2037">2037</option>
                                    <option value="2038">2038</option>
                                    <option value="2039">2039</option>
                                    <option value="2040">2040</option>
                                    <option value="2041">2041</option>
                                    <option value="2042">2042</option>
                                    <option value="2043">2043</option>
                                    <option value="2044">2044</option>
                                    <option value="2045">2045</option>
                                    <option value="2046">2046</option>
                                    <option value="2047">2047</option>
                                    <option value="2048">2048</option>
                                    <option value="2049">2049</option>
                                    <option value="2050">2050</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form2" class="btn btn-primary btn-lg">Email PaySlip</button>
                        </div>
                    </div>
                </form>

                <form name="hak_form3" action="actions3.php" target="_blank" method="post">
                    <div class="row  mt-5">
                        <div class="col-1">
                            <input class="form-control" name="hak_id3" placeholder="Enter ID">
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_month3">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="rs-select2--light rs-select2--sm mt-1" style="min-width: 100%">
                                <select class="js-select2" name="hak_year3">
                                    <option value="">Select Year</option>
                            <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                    <option value="2032">2032</option>
                                    <option value="2033">2033</option>
                                    <option value="2034">2034</option>
                                    <option value="2035">2035</option>
                                    <option value="2036">2036</option>
                                    <option value="2037">2037</option>
                                    <option value="2038">2038</option>
                                    <option value="2039">2039</option>
                                    <option value="2040">2040</option>
                                    <option value="2041">2041</option>
                                    <option value="2042">2042</option>
                                    <option value="2043">2043</option>
                                    <option value="2044">2044</option>
                                    <option value="2045">2045</option>
                                    <option value="2046">2046</option>
                                    <option value="2047">2047</option>
                                    <option value="2048">2048</option>
                                    <option value="2049">2049</option>
                                    <option value="2050">2050</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form3" class="btn btn-primary btn-lg">Generate  Payslip</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
 </section>
</div>
         
     <?php include 'includes/footer.php'; ?>
  
<?php include 'includes/scripts.php'; ?>
<!-- Jquery JS-->
<script src="vendor1/jquery-3.2.1.min.js"></script>
<!-- Bootstrap JS-->
<script src="vendor1/bootstrap-4.1/popper.min.js"></script>
<script src="vendor1/bootstrap-4.1/bootstrap.min.js"></script>
<!-- Vendor JS       -->
<script src="vendor1/slick/slick.min.js">
</script>
<script src="vendor1/wow/wow.min.js"></script>
<script src="vendor1/animsition/animsition.min.js"></script>
<script src="vendor1/bootstrap-progressbar/bootstrap-progressbar.min.js">
</script>
<script src="vendor1/counter-up/jquery.waypoints.min.js"></script>
<script src="vendor1/counter-up/jquery.counterup.min.js">
</script>
<script src="vendor1/circle-progress/circle-progress.min.js"></script>
<script src="vendor1/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="vendor1/chartjs/Chart.bundle.min.js"></script>
<script src="vendor1/select2/select2.min.js">
</script>

<!-- Main JS-->
<script src="js/main.js"></script>
<script src="js/datatables/jquery.dataTables.min.js"></script>
<script src="js/datatables/datatables.min.js"></script>
<script>
    $(function () {
        $('#tableId').DataTable();
        if ($('.datatable').length > 0) {
            $('.datatable').DataTable({
                "bFilter": false,
                "lengthMenu": [[20,50,100, 200, 300, -1], [20,50,100, 200, 300, "All"]],
                "filter": true,
                "searching": true,
            });
        }
    });
</script>

</body>

</html>
<!-- end document-->


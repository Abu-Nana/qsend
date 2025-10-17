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
$query = mysqli_query($dbconnection,"select *from payslip");
?>
<body>
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
                                <th>Email</th>
                                <th>Month</th>
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
                                    <td><label class="au-checkbox"><input type="checkbox" name="hak_select[]" class="hak_cb" value="<?php echo $row['email'];?>"><span class="au-checkmark"></span></label></td>
                                    <td><?php echo $row['ID'];?></td>
                                    <td><?php echo $row['email'];?></td>
                                    <td><?php echo $row['month'];?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="row">
                        <h4 class="text-danger col-md-12">* Select Only when you cahnge the month if you don't want to change the month so don't need to change it</h4>
                        <div class="rs-select2--light rs-select2--sm w-100 mt-1 col-md-2" style="min-width: 25%">
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

                        <div class="col-md-1">
                            <input class="form-control" name="hak_year" placeholder="Enter Year">
                        </div>
                        <button type="submit" name="hak_form" class="btn btn-primary btn-lg text-left com-md-4 ml-5">Send PDF Email</button>
                    </div>
                </form>
                <form name="hak_form2" action="actions2.php" target="_blank" method="post">
                    <div class="row  mt-5">
                        <div class="col-md-1">
                            <input class="form-control" name="hak_id2" placeholder="Enter ID">
                        </div>
                        <div class="col-md-3">
                            <div class="rs-select2--light rs-select2--sm w-100 mt-1 col-md-2" style="min-width: 100%">
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
                        <div class="col-md-1">
                            <input class="form-control" name="hak_year2" placeholder="Enter Year">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form2" class="btn btn-primary btn-lg">Send PDF Email</button>
                        </div>
                    </div>
                </form>

                <form name="hak_form3" action="actions3.php" target="_blank" method="post">
                    <div class="row  mt-5">
                        <div class="col-md-1">
                            <input class="form-control" name="hak_id3" placeholder="Enter ID">
                        </div>
                        <div class="col-md-2">
                            <div class="rs-select2--light rs-select2--sm mt-1 col-md-8" style="min-width: 100%">
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
                        <div class="col-md-1">
                            <input class="form-control" name="hak_year3" placeholder="Enter Year">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="hak_form3" class="btn btn-primary btn-lg">Generate PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

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
                "lengthMenu": [[100, 200, 300, -1], [100, 200, 300, "All"]],
            });
        }
    });
</script>

</body>

</html>
<!-- end document-->

<?php include 'includes/session.php'; ?>
<?php 
  include '../timezone.php'; 
  $today = date('Y-m-d');
  $year = date('Y');
  if(isset($_GET['year'])){
    $year = $_GET['year'];
  }
?>
<?php include 'includes/header.php'; ?>
<style>
/* Modern Dashboard Styles */
.elegant-stat-card {
  border-radius: 15px;
  padding: 25px;
  margin-bottom: 30px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  border: none;
  background: linear-gradient(135deg, var(--card-bg-start), var(--card-bg-end));
}

.elegant-stat-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.elegant-stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, rgba(255,255,255,0.6), rgba(255,255,255,0.2));
}

.elegant-stat-card .stat-icon {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 70px;
  opacity: 0.15;
  transition: all 0.3s ease;
}

.elegant-stat-card:hover .stat-icon {
  opacity: 0.25;
  transform: translateY(-50%) scale(1.1) rotate(5deg);
}

.elegant-stat-card .stat-number {
  font-size: 42px;
  font-weight: 700;
  margin-bottom: 8px;
  color: #fff;
  text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.elegant-stat-card .stat-label {
  font-size: 14px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: rgba(255,255,255,0.95);
  margin-bottom: 15px;
}

.elegant-stat-card .stat-footer {
  margin-top: 15px;
  padding-top: 15px;
  border-top: 1px solid rgba(255,255,255,0.2);
}

.elegant-stat-card .stat-footer a {
  color: #fff;
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: all 0.3s ease;
}

.elegant-stat-card .stat-footer a:hover {
  letter-spacing: 1px;
}

/* Color Schemes for Cards */
.card-aqua {
  --card-bg-start: #00c6ff;
  --card-bg-end: #0072ff;
}

.card-green {
  --card-bg-start: #11998e;
  --card-bg-end: #38ef7d;
}

.card-yellow {
  --card-bg-start: #f2994a;
  --card-bg-end: #f2c94c;
}

.card-red {
  --card-bg-start: #eb3349;
  --card-bg-end: #f45c43;
}

.card-purple {
  --card-bg-start: #667eea;
  --card-bg-end: #764ba2;
}

.card-orange {
  --card-bg-start: #fa709a;
  --card-bg-end: #fee140;
}

.card-teal {
  --card-bg-start: #4facfe;
  --card-bg-end: #00f2fe;
}

.card-pink {
  --card-bg-start: #ff6a88;
  --card-bg-end: #ff99ac;
}

/* Dashboard Header Styles */
.dashboard-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 30px;
  border-radius: 15px;
  margin-bottom: 30px;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.dashboard-header h1 {
  margin: 0;
  font-size: 32px;
  font-weight: 700;
  color: white;
}

.dashboard-header p {
  margin: 5px 0 0 0;
  opacity: 0.9;
  font-size: 16px;
}

/* Chart Container Enhancement */
.chart-container-elegant {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}

.chart-container-elegant:hover {
  box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  padding-bottom: 20px;
  border-bottom: 2px solid #f0f0f0;
}

.chart-header h3 {
  margin: 0;
  font-size: 22px;
  font-weight: 700;
  color: #2c3e50;
}

/* Animated Background */
.content-wrapper {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
}

/* Alert Enhancements */
.alert-modern {
  border-radius: 10px;
  border: none;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  padding: 20px;
  animation: slideInDown 0.5s ease;
}

@keyframes slideInDown {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Breadcrumb Enhancement */
.breadcrumb {
  background: white;
  border-radius: 10px;
  padding: 15px 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Fade-in Animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.elegant-stat-card {
  animation: fadeInUp 0.6s ease forwards;
  opacity: 0;
}

.elegant-stat-card:nth-child(1) { animation-delay: 0.1s; }
.elegant-stat-card:nth-child(2) { animation-delay: 0.2s; }
.elegant-stat-card:nth-child(3) { animation-delay: 0.3s; }
.elegant-stat-card:nth-child(4) { animation-delay: 0.4s; }
.elegant-stat-card:nth-child(5) { animation-delay: 0.5s; }
.elegant-stat-card:nth-child(6) { animation-delay: 0.6s; }
.elegant-stat-card:nth-child(7) { animation-delay: 0.7s; }
.elegant-stat-card:nth-child(8) { animation-delay: 0.8s; }
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  	<?php include 'includes/navbar.php'; ?>
  	<?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="dashboard-header">
        <h1>
          <i class="fa fa-dashboard"></i> Dashboard
        </h1>
        <p>Welcome back! Here's what's happening with your system today.</p>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible alert-modern'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible alert-modern'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <?php
          // Assuming $user is already set with the current user's details
          $cat = $user['cat'];

          if ($cat == 'dea') {
            // Modern elegant dashboard for 'dea'
            ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-aqua">
                <i class="fa fa-building stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT * FROM study_centers";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Study Centre Counts</div>
                <div class="stat-footer">
                  <a href="centres.php">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-green">
                <i class="fa fa-envelope stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT * FROM files where sem=251";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Questions Sent</div>
                <div class="stat-footer">
                  <a href="sentitems.php">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-yellow">
                <i class="fa fa-pencil-square-o stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT * FROM study_centers where study_centre_email is not null";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Centres Writing Exam</div>
                <div class="stat-footer">
                  <a href="staff-view.php">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-red">
                <i class="fa fa-users stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT * FROM student_registrations";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Student Registrations</div>
                <div class="stat-footer">
                  <a href="reg-data.php">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-purple">
                <i class="fa fa-star stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT * FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Total Scores Uploaded</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-teal">
                <i class="fa fa-book stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT DISTINCT crscode FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Total Courses Uploaded</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-orange">
                <i class="fa fa-upload stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT DISTINCT stc FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Study Centres Uploaded</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-pink">
                <i class="fa fa-check-square stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT DISTINCT marker FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Total Markers</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            <?php
          } else if ($cat == 'others') {
            // Modern elegant dashboard for 'others'
            ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-purple">
                <i class="fa fa-star stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT * FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Total Scores Uploaded</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-teal">
                <i class="fa fa-book stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT DISTINCT crscode FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Total Courses Uploaded</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-orange">
                <i class="fa fa-upload stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT DISTINCT stc FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Study Centres Uploaded</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="elegant-stat-card card-pink">
                <i class="fa fa-check-square stat-icon"></i>
                <div class="stat-number">
                  <?php
                    $sql = "SELECT DISTINCT marker FROM scores";
                    $query = $conn->query($sql);
                    echo $query->num_rows;
                  ?>
                </div>
                <div class="stat-label">Total Markers</div>
                <div class="stat-footer">
                  <a href="#">
                    <span>View Details</span>
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
            <?php
          }
        ?>
      </div>
      <!-- /.row -->
      <?php if ($cat == 'idea') { ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="chart-container-elegant">
            <div class="chart-header">
              <h3><i class="fa fa-bar-chart"></i> Question Sending Statistics</h3>
              <div class="box-tools">
                <form class="form-inline">
                  <div class="form-group">
                    <label style="margin-right: 10px; font-weight: 600; color: #34495e;">
                      <i class="fa fa-calendar"></i> Select Year: 
                    </label>
                    <select class="form-control" id="select_year" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 8px 15px;">
                      <?php
                        for($i=2015; $i<=2065; $i++){
                          $selected = ($i==$year)?'selected':'';
                          echo "
                            <option value='".$i."' ".$selected.">".$i."</option>
                          ";
                        }
                      ?>
                    </select>
                  </div>
                </form>
              </div>
            </div>
            <div class="chart-body" style="padding: 20px 10px;">
              <div class="chart">
                <div id="legend" class="text-center" style="margin-bottom: 20px;"></div>
                <canvas id="barChart" style="height:400px; max-height: 400px;"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
      </section>
      <!-- right col -->
    </div>
  	<?php include 'includes/footer.php'; ?>

</div>
<!-- ./wrapper -->

<!-- Chart Data -->
<?php if ($cat == 'idea') { ?>
<?php
  $and = 'AND YEAR(date) = '.$year;
  $months = array();
  $ontime = array();
  $late = array();
  for( $m = 1; $m <= 12; $m++ ) {
    $sql = "SELECT * FROM payslip WHERE year(month) = '$m' AND month != '-' or month is not null $and";
    $oquery = $conn->query($sql);
    array_push($ontime, $oquery->num_rows);

    $sql = "SELECT * FROM payslip WHERE month(year) = '$m' AND year != '-' or month is not null $and";
    $lquery = $conn->query($sql);
    array_push($late, $lquery->num_rows);

    $num = str_pad( $m, 2, 0, STR_PAD_LEFT );
    $month =  date('M', mktime(0, 0, 0, $m, 1));
    array_push($months, $month);
  }

  $months = json_encode($months);
  $late = json_encode($late);
  $ontime = json_encode($ontime);

?>
<?php } ?>
<!-- End Chart Data -->
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  var barChartCanvas = $('#barChart').get(0).getContext('2d')
  var barChart = new Chart(barChartCanvas)
  var barChartData = {
    labels  : <?php echo $months; ?>,
    datasets: [
      {
        label               : 'Years',
        fillColor           : 'rgba(210, 214, 222, 1)',
        strokeColor         : 'rgba(210, 214, 222, 1)',
        pointColor          : 'rgba(210, 214, 222, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : <?php echo $late; ?>
      },
      {
        label               : 'Months',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : <?php echo $ontime; ?>
      }
    ]
  }
  barChartData.datasets[1].fillColor   = '#00a65a'
  barChartData.datasets[1].strokeColor = '#00a65a'
  barChartData.datasets[1].pointColor  = '#00a65a'
  var barChartOptions                  = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero        : true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - If there is a stroke on each bar
    barShowStroke           : true,
    //Number - Pixel width of the bar stroke
    barStrokeWidth          : 2,
    //Number - Spacing between each of the X value sets
    barValueSpacing         : 5,
    //Number - Spacing between data sets within X values
    barDatasetSpacing       : 1,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to make the chart responsive
    responsive              : true,
    maintainAspectRatio     : true
  }

  barChartOptions.datasetFill = false
  var myChart = barChart.Bar(barChartData, barChartOptions)
  document.getElementById('legend').innerHTML = myChart.generateLegend();
});
</script>
<script>
$(function(){
  $('#select_year').change(function(){
    window.location.href = 'home.php?year='+$(this).val();
  });
});
</script>
</body>
</html>

<?php 
/**
 * Enhanced Registration Data Page
 * 
 * Modern UI with statistics, filters, export, and better data management
 * 
 * @package QSEND
 * @version 2.0
 */

include 'includes/session.php'; 
include 'includes/header.php'; 
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-database"></i> Registration Data
        <small>View and manage student registrations</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Registration Data</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        // Display session messages
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

      <!-- Statistics Cards -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <?php
                $total_students = $conn->query("SELECT COUNT(*) as total FROM student_registrations")->fetch_assoc()['total'];
                echo "<h3>$total_students</h3>";
              ?>
              <p>Total Students</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <?php
                $unique_centers = $conn->query("SELECT COUNT(DISTINCT study_center) as total FROM student_registrations")->fetch_assoc()['total'];
                echo "<h3>$unique_centers</h3>";
              ?>
              <p>Study Centers</p>
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <?php
                $unique_courses = $conn->query("SELECT COUNT(DISTINCT course) as total FROM student_registrations")->fetch_assoc()['total'];
                echo "<h3>$unique_courses</h3>";
              ?>
              <p>Courses</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <?php
                $unique_sessions = $conn->query("SELECT COUNT(DISTINCT exam_session) as total FROM student_registrations")->fetch_assoc()['total'];
                echo "<h3>$unique_sessions</h3>";
              ?>
              <p>Exam Sessions</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Advanced Filters -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-filter"></i> Advanced Filters</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                  <i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
            <div class="box-body" style="display: none;">
              <form id="filterForm" method="GET">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Study Center</label>
                      <select class="form-control select2" name="center" id="centerFilter">
                        <option value="">All Centers</option>
                        <?php
                          $centers = $conn->query("SELECT DISTINCT study_center FROM student_registrations ORDER BY study_center");
                          while($center = $centers->fetch_assoc()) {
                            $selected = (isset($_GET['center']) && $_GET['center'] == $center['study_center']) ? 'selected' : '';
                            echo "<option value='{$center['study_center']}' $selected>{$center['study_center']}</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Exam Session</label>
                      <select class="form-control select2" name="session" id="sessionFilter">
                        <option value="">All Sessions</option>
                        <?php
                          $sessions = $conn->query("SELECT DISTINCT exam_session FROM student_registrations ORDER BY exam_session DESC");
                          while($session = $sessions->fetch_assoc()) {
                            $selected = (isset($_GET['session']) && $_GET['session'] == $session['exam_session']) ? 'selected' : '';
                            echo "<option value='{$session['exam_session']}' $selected>{$session['exam_session']}</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Course</label>
                      <select class="form-control select2" name="course" id="courseFilter">
                        <option value="">All Courses</option>
                        <?php
                          $courses = $conn->query("SELECT DISTINCT course FROM student_registrations ORDER BY course");
                          while($course = $courses->fetch_assoc()) {
                            $selected = (isset($_GET['course']) && $_GET['course'] == $course['course']) ? 'selected' : '';
                            echo "<option value='{$course['course']}' $selected>{$course['course']}</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>&nbsp;</label><br>
                      <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Apply Filters
                      </button>
                      <button type="button" class="btn btn-default" onclick="clearFilters()">
                        <i class="fa fa-refresh"></i> Clear
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Data Table -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-list"></i> Student Registration Records
                <span class="badge bg-blue" id="totalCount">0</span>
              </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-success btn-sm" onclick="exportToExcel()">
                  <i class="fa fa-file-excel-o"></i> Export Excel
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="printTable()">
                  <i class="fa fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmTruncate()">
                  <i class="fa fa-trash"></i> Clear All Data
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table id="regDataTable" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th><i class="fa fa-hashtag"></i> #</th>
                      <th><i class="fa fa-id-card"></i> Matric Number</th>
                      <th><i class="fa fa-university"></i> Study Center</th>
                      <th><i class="fa fa-building"></i> Center Code</th>
                      <th><i class="fa fa-book"></i> Course</th>
                      <th><i class="fa fa-calendar-o"></i> Exam Day</th>
                      <th><i class="fa fa-calendar-check-o"></i> Session</th>
                      <th><i class="fa fa-calendar"></i> Exam Date</th>
                      <th><i class="fa fa-cogs"></i> Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      // Build dynamic query based on filters
                      $where_conditions = [];
                      $params = [];
                      $param_types = "";
                      
                      if(isset($_GET['center']) && !empty($_GET['center'])) {
                        $where_conditions[] = "study_center = ?";
                        $params[] = $_GET['center'];
                        $param_types .= "s";
                      }
                      
                      if(isset($_GET['session']) && !empty($_GET['session'])) {
                        $where_conditions[] = "exam_session = ?";
                        $params[] = $_GET['session'];
                        $param_types .= "s";
                      }
                      
                      if(isset($_GET['course']) && !empty($_GET['course'])) {
                        $where_conditions[] = "course = ?";
                        $params[] = $_GET['course'];
                        $param_types .= "s";
                      }
                      
                      $sql = "SELECT *, student_registrations.id AS id FROM student_registrations";
                      if(!empty($where_conditions)) {
                        $sql .= " WHERE " . implode(" AND ", $where_conditions);
                      }
                      $sql .= " ORDER BY id DESC";
                      
                      if(!empty($params)) {
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param($param_types, ...$params);
                        $stmt->execute();
                        $query = $stmt->get_result();
                      } else {
                        $query = $conn->query($sql);
                      }
                      
                      $counter = 1;
                      while($fetch = $query->fetch_assoc()) {
                    ?>
                    <tr>
                      <td><?php echo $counter++; ?></td>
                      <td>
                        <strong><?php echo htmlspecialchars($fetch['matric_number']); ?></strong>
                      </td>
                      <td><?php echo htmlspecialchars($fetch['study_center']); ?></td>
                      <td>
                        <span class="label label-info"><?php echo htmlspecialchars($fetch['study_center_code']); ?></span>
                      </td>
                      <td>
                        <span class="label label-primary"><?php echo htmlspecialchars($fetch['course']); ?></span>
                      </td>
                      <td><?php echo htmlspecialchars($fetch['exam_day']); ?></td>
                      <td>
                        <span class="label label-success"><?php echo htmlspecialchars($fetch['exam_session']); ?></span>
                      </td>
                      <td><?php echo htmlspecialchars($fetch['exa_date']); ?></td>
                      <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-info btn-xs" onclick="viewDetails(<?php echo $fetch['id']; ?>)">
                            <i class="fa fa-eye"></i>
                          </button>
                          <button type="button" class="btn btn-warning btn-xs" onclick="editRecord(<?php echo $fetch['id']; ?>)">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" class="btn btn-danger btn-xs" onclick="deleteRecord(<?php echo $fetch['id']; ?>)">
                            <i class="fa fa-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>

</div>

<!-- Scripts -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../bower_components/select2/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#regDataTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 25,
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": [8] }
        ],
        "language": {
            "processing": "Loading data...",
            "lengthMenu": "Show _MENU_ entries per page",
            "zeroRecords": "No matching records found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "No entries available",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "search": "Search:",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });
    
    // Update total count
    $('#totalCount').text(table.page.info().recordsTotal);
    
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });
});

function clearFilters() {
    $('#filterForm')[0].reset();
    $('.select2').val(null).trigger('change');
    window.location.href = 'reg-data.php';
}

function exportToExcel() {
    window.open('export_regdata.php', '_blank');
}

function printTable() {
    window.print();
}

function confirmTruncate() {
    if(confirm('⚠️ WARNING: This will DELETE ALL registration data!\n\nAre you absolutely sure you want to continue?\n\nThis action CANNOT be undone!')) {
        if(confirm('FINAL CONFIRMATION: Click OK to permanently delete all data.')) {
            window.location.href = 'truncate_regdata.php';
        }
    }
}

function viewDetails(id) {
    alert('View details for record ID: ' + id);
    // Implement view details modal
}

function editRecord(id) {
    alert('Edit record ID: ' + id);
    // Implement edit functionality
}

function deleteRecord(id) {
    if(confirm('Are you sure you want to delete this record?')) {
        window.location.href = 'delete_regdata.php?id=' + id;
    }
}
</script>

<style>
/* Enhanced UI Styles */
.small-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.small-box:hover {
    transform: translateY(-2px);
}

.box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.label {
    font-size: 11px;
    padding: 4px 8px;
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 12px;
    padding: 4px 8px;
}

/* Print styles */
@media print {
    .box-tools,
    .btn-group,
    .box-header,
    .small-box-footer,
    .content-header {
        display: none !important;
    }
    
    .table {
        font-size: 10px;
    }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        border: none;
    }
    
    .btn-group .btn {
        margin-bottom: 2px;
    }
}
</style>

</body>
</html>

<?php 
/**
 * Enhanced Sent Items Page
 * 
 * Modern UI with statistics, performance optimizations, and better UX
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
        <i class="fa fa-paper-plane"></i> Sent Questions
        <small>View and manage sent examination questions</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sent Questions</li>
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
                $total_sent = $conn->query("SELECT COUNT(*) as total FROM files WHERE sem=251")->fetch_assoc()['total'];
                echo "<h3>$total_sent</h3>";
              ?>
              <p>Total Sent</p>
            </div>
            <div class="icon">
              <i class="fa fa-paper-plane"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <?php
                $today_sent = $conn->query("SELECT COUNT(*) as total FROM files WHERE sem=251 AND DATE(created_at) = CURDATE()")->fetch_assoc()['total'];
                echo "<h3>$today_sent</h3>";
              ?>
              <p>Sent Today</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <?php
                $this_week = $conn->query("SELECT COUNT(*) as total FROM files WHERE sem=251 AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_assoc()['total'];
                echo "<h3>$this_week</h3>";
              ?>
              <p>This Week</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar-week"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <?php
                $unique_centers = $conn->query("SELECT COUNT(DISTINCT student_center_name) as total FROM files WHERE sem=251")->fetch_assoc()['total'];
                echo "<h3>$unique_centers</h3>";
              ?>
              <p>Centers</p>
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Advanced Filters -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-filter"></i> Advanced Filters</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                  <i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <form id="filterForm" method="GET">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Center Name</label>
                      <select class="form-control select2" name="center" id="centerFilter">
                        <option value="">All Centers</option>
                        <?php
                          $centers = $conn->query("SELECT DISTINCT student_center_name FROM files WHERE sem=251 ORDER BY student_center_name");
                          while($center = $centers->fetch_assoc()) {
                            $selected = (isset($_GET['center']) && $_GET['center'] == $center['student_center_name']) ? 'selected' : '';
                            echo "<option value='{$center['student_center_name']}' $selected>{$center['student_center_name']}</option>";
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
                          $sessions = $conn->query("SELECT DISTINCT exam_session FROM files WHERE sem=251 ORDER BY exam_session DESC");
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
                      <label>Date Range</label>
                      <input type="text" class="form-control" id="dateRange" name="date_range" placeholder="Select date range">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>&nbsp;</label><br>
                      <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Filter
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
                <i class="fa fa-list"></i> Sent Questions List
                <span class="badge bg-blue" id="totalCount">0</span>
              </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-success btn-sm" onclick="exportToExcel()">
                  <i class="fa fa-file-excel-o"></i> Export Excel
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="refreshTable()">
                  <i class="fa fa-refresh"></i> Refresh
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table id="sentItemsTable" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th><i class="fa fa-hashtag"></i> #</th>
                      <th><i class="fa fa-building"></i> Center Code</th>
                      <th><i class="fa fa-university"></i> Center Name</th>
                      <th><i class="fa fa-calendar"></i> Exam Session</th>
                      <th><i class="fa fa-clock-o"></i> Exam Day</th>
                      <th><i class="fa fa-key"></i> Password</th>
                      <th><i class="fa fa-calendar-check-o"></i> Date Sent</th>
                      <th><i class="fa fa-user"></i> Sent By</th>
                      <th><i class="fa fa-globe"></i> IP Address</th>
                      <th><i class="fa fa-download"></i> File</th>
                      <th><i class="fa fa-cogs"></i> Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      // Build dynamic query based on filters
                      $where_conditions = ["sem=251"];
                      $params = [];
                      $param_types = "";
                      
                      if(isset($_GET['center']) && !empty($_GET['center'])) {
                        $where_conditions[] = "student_center_name = ?";
                        $params[] = $_GET['center'];
                        $param_types .= "s";
                      }
                      
                      if(isset($_GET['session']) && !empty($_GET['session'])) {
                        $where_conditions[] = "exam_session = ?";
                        $params[] = $_GET['session'];
                        $param_types .= "s";
                      }
                      
                      if(isset($_GET['date_range']) && !empty($_GET['date_range'])) {
                        $dates = explode(' - ', $_GET['date_range']);
                        if(count($dates) == 2) {
                          $where_conditions[] = "DATE(created_at) BETWEEN ? AND ?";
                          $params[] = $dates[0];
                          $params[] = $dates[1];
                          $param_types .= "ss";
                        }
                      }
                      
                      $sql = "SELECT *, files.id AS id FROM files WHERE " . implode(" AND ", $where_conditions) . " ORDER BY created_at DESC";
                      
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
                        $file_path = $fetch['file_name'];
                        $file_exists = file_exists($file_path);
                        $file_icon = $file_exists ? 'fa-file-pdf-o' : 'fa-file-o';
                        $file_class = $file_exists ? 'text-success' : 'text-danger';
                    ?>
                    <tr>
                      <td><?php echo $counter++; ?></td>
                      <td>
                        <span class="label label-info"><?php echo htmlspecialchars($fetch['student_center_name']); ?></span>
                      </td>
                      <td>
                        <strong><?php echo htmlspecialchars($fetch['study_center']); ?></strong>
                      </td>
                      <td>
                        <span class="label label-primary"><?php echo htmlspecialchars($fetch['exam_session']); ?></span>
                      </td>
                      <td>
                        <span class="label label-warning"><?php echo htmlspecialchars($fetch['exam_day']); ?></span>
                      </td>
                      <td>
                        <code><?php echo htmlspecialchars($fetch['password']); ?></code>
                      </td>
                      <td>
                        <i class="fa fa-calendar"></i> 
                        <?php echo date('M d, Y', strtotime($fetch['created_at'])); ?><br>
                        <small class="text-muted">
                          <i class="fa fa-clock-o"></i> 
                          <?php echo date('h:i A', strtotime($fetch['created_at'])); ?>
                        </small>
                      </td>
                      <td>
                        <i class="fa fa-user"></i> 
                        <?php echo htmlspecialchars($fetch['sentby']); ?>
                      </td>
                      <td>
                        <code><?php echo htmlspecialchars($fetch['ip_address']); ?></code>
                      </td>
                      <td>
                        <?php if($file_exists): ?>
                          <a href="<?php echo $file_path; ?>" class="btn btn-success btn-xs" target="_blank">
                            <i class="fa fa-download"></i> Download
                          </a>
                        <?php else: ?>
                          <span class="text-danger">
                            <i class="fa fa-exclamation-triangle"></i> File Missing
                          </span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-info btn-xs" onclick="viewDetails(<?php echo $fetch['id']; ?>)">
                            <i class="fa fa-eye"></i>
                          </button>
                          <button type="button" class="btn btn-warning btn-xs" onclick="editItem(<?php echo $fetch['id']; ?>)">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" class="btn btn-danger btn-xs" onclick="deleteItem(<?php echo $fetch['id']; ?>)">
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

<!-- Enhanced Scripts -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../bower_components/select2/dist/js/select2.min.js"></script>
<script src="../bower_components/moment/moment.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced features
    var table = $('#sentItemsTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "processing": true,
        "pageLength": 25,
        "order": [[6, "desc"]], // Sort by date sent
        "columnDefs": [
            { "orderable": false, "targets": [10] } // Disable sorting on actions column
        ],
        "language": {
            "processing": "Loading data...",
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "No matching records found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "search": "Search:",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    
    // Update total count
    $('#totalCount').text(table.page.info().recordsTotal);
    
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });
    
    // Initialize date range picker
    $('#dateRange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD'
        }
    });
    
    $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    
    $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    
    // Auto-refresh every 5 minutes
    setInterval(function() {
        table.ajax.reload(null, false);
    }, 300000);
});

function clearFilters() {
    $('#filterForm')[0].reset();
    $('.select2').val(null).trigger('change');
    window.location.href = 'sentitems.php';
}

function refreshTable() {
    $('#sentItemsTable').DataTable().ajax.reload();
}

function exportToExcel() {
    window.open('export_sentitems.php', '_blank');
}

function viewDetails(id) {
    // Implement view details modal
    alert('View details for ID: ' + id);
}

function editItem(id) {
    // Implement edit functionality
    alert('Edit item ID: ' + id);
}

function deleteItem(id) {
    if(confirm('Are you sure you want to delete this item?')) {
        // Implement delete functionality
        alert('Delete item ID: ' + id);
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

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-top: none;
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

.box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge {
    font-size: 12px;
    padding: 4px 8px;
}

/* Loading animation */
.dataTables_processing {
    background: rgba(255,255,255,0.9);
    border-radius: 4px;
    padding: 20px;
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


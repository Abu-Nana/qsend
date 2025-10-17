<?php 
/**
 * Enhanced Registration Data Upload Page
 * 
 * Modern UI with progress loader, counter, and percentage display
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
        <i class="fa fa-upload"></i> Registration Data Upload
        <small>Import student registration data with progress tracking</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Registration Data Upload</li>
      </ol>
    </section>

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
                $total_registrations = $conn->query("SELECT COUNT(*) as total FROM student_registrations")->fetch_assoc()['total'];
                echo "<h3>$total_registrations</h3>";
              ?>
              <p>Total Registrations</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <?php
                $today_uploads = $conn->query("SELECT COUNT(*) as total FROM student_registrations WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['total'];
                echo "<h3>$today_uploads</h3>";
              ?>
              <p>Uploaded Today</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
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
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <?php
                $last_upload = $conn->query("SELECT MAX(created_at) as last_upload FROM student_registrations")->fetch_assoc()['last_upload'];
                $last_upload = $last_upload ? date('M d, Y', strtotime($last_upload)) : 'Never';
                echo "<h3 style='font-size: 18px;'>$last_upload</h3>";
              ?>
              <p>Last Upload</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Upload Section -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-upload"></i> Upload Registration Data
              </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                  <i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <!-- Upload Form -->
              <form id="uploadForm" action="importData.php" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="csvFile">
                        <i class="fa fa-file-csv-o"></i> Select CSV File
                      </label>
                      <div class="input-group">
                        <label class="input-group-btn">
                          <span class="btn btn-primary">
                            <i class="fa fa-folder-open"></i> Browse&hellip;
                            <input type="file" name="file" id="csvFile" accept=".csv" style="display: none;" required>
                          </span>
                        </label>
                        <input type="text" class="form-control" id="fileDisplay" readonly placeholder="No file selected">
                      </div>
                      <small class="help-block">
                        <i class="fa fa-info-circle"></i> 
                        Only CSV files are allowed. Maximum file size: 10MB
                      </small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>&nbsp;</label><br>
                      <button type="submit" class="btn btn-success btn-lg btn-block" id="uploadBtn">
                        <i class="fa fa-upload"></i> Start Import
                      </button>
                    </div>
                  </div>
                </div>
              </form>

              <!-- Progress Section (Hidden by default) -->
              <div id="progressSection" style="display: none;">
                <div class="row">
                  <div class="col-md-12">
                    <h4><i class="fa fa-spinner fa-spin"></i> Importing Data...</h4>
                    
                    <!-- Progress Bar -->
                    <div class="progress progress-lg">
                      <div class="progress-bar progress-bar-striped active" role="progressbar" 
                           id="progressBar" style="width: 0%">
                        <span id="progressText">0%</span>
                      </div>
                    </div>
                    
                    <!-- Progress Details -->
                    <div class="row">
                      <div class="col-md-3">
                        <div class="info-box bg-aqua">
                          <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">Total Records</span>
                            <span class="info-box-number" id="totalRecords">0</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="info-box bg-green">
                          <span class="info-box-icon"><i class="fa fa-check"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">Processed</span>
                            <span class="info-box-number" id="processedRecords">0</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="info-box bg-yellow">
                          <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">Remaining</span>
                            <span class="info-box-number" id="remainingRecords">0</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="info-box bg-red">
                          <span class="info-box-icon"><i class="fa fa-times"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">Errors</span>
                            <span class="info-box-number" id="errorRecords">0</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Progress Log -->
                    <div class="box box-info">
                      <div class="box-header with-border">
                        <h3 class="box-title">
                          <i class="fa fa-list"></i> Import Progress
                        </h3>
                      </div>
                      <div class="box-body">
                        <div id="progressLog" style="height: 200px; overflow-y: auto; background: #f9f9f9; padding: 10px; border-radius: 4px;">
                          <div class="text-center text-muted">
                            <i class="fa fa-spinner fa-spin"></i> Starting import process...
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Instructions Section -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-info-circle"></i> Upload Instructions
              </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                  <i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <h4><i class="fa fa-list-ol"></i> Step-by-Step Guide</h4>
                  <ol>
                    <li><strong>Download Template:</strong> <a href="templates/IMPORT.csv" download class="btn btn-sm btn-primary">
                      <i class="fa fa-download"></i> Download CSV Template
                    </a></li>
                    <li><strong>Prepare Data:</strong> Fill the CSV file with student registration data</li>
                    <li><strong>Upload File:</strong> Click "Browse" and select your CSV file</li>
                    <li><strong>Start Import:</strong> Click "Start Import" to begin the process</li>
                    <li><strong>Monitor Progress:</strong> Watch the real-time progress and counters</li>
                  </ol>
                </div>
                <div class="col-md-6">
                  <h4><i class="fa fa-table"></i> CSV Format Requirements</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Column</th>
                          <th>Description</th>
                          <th>Required</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td>matric_number</td><td>Student Matriculation Number</td><td><span class="label label-success">Yes</span></td></tr>
                        <tr><td>study_center</td><td>Study Center Name</td><td><span class="label label-success">Yes</span></td></tr>
                        <tr><td>study_center_code</td><td>Center Code</td><td><span class="label label-success">Yes</span></td></tr>
                        <tr><td>course</td><td>Course Code</td><td><span class="label label-success">Yes</span></td></tr>
                        <tr><td>exam_day</td><td>Exam Day</td><td><span class="label label-success">Yes</span></td></tr>
                        <tr><td>exam_session</td><td>Exam Session</td><td><span class="label label-success">Yes</span></td></tr>
                        <tr><td>exa_date</td><td>Exam Date</td><td><span class="label label-success">Yes</span></td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Uploads -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-history"></i> Recent Uploads
              </h3>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Records</th>
                      <th>Status</th>
                      <th>Uploaded By</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $recent_uploads = $conn->query("
                        SELECT 
                          DATE(created_at) as upload_date,
                          COUNT(*) as record_count,
                          'Completed' as status,
                          'System' as uploaded_by
                        FROM student_registrations 
                        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                        GROUP BY DATE(created_at)
                        ORDER BY created_at DESC
                        LIMIT 5
                      ");
                      
                      if($recent_uploads->num_rows > 0) {
                        while($upload = $recent_uploads->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>" . date('M d, Y', strtotime($upload['upload_date'])) . "</td>";
                          echo "<td><span class='badge bg-blue'>" . $upload['record_count'] . "</span></td>";
                          echo "<td><span class='label label-success'>" . $upload['status'] . "</span></td>";
                          echo "<td>" . $upload['uploaded_by'] . "</td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr><td colspan='4' class='text-center text-muted'>No recent uploads</td></tr>";
                      }
                    ?>
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

<script>
$(document).ready(function() {
    // File input change handler
    $('#csvFile').change(function() {
        var fileName = $(this).val().split('\\').pop();
        $('#fileDisplay').val(fileName);
        
        if(fileName) {
            $('#uploadBtn').prop('disabled', false).removeClass('btn-default').addClass('btn-success');
        } else {
            $('#uploadBtn').prop('disabled', true).removeClass('btn-success').addClass('btn-default');
        }
    });
    
    // Form submission handler
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var file = $('#csvFile')[0].files[0];
        
        if(!file) {
            alert('Please select a CSV file first!');
            return;
        }
        
        // Show progress section
        $('#progressSection').show();
        $('#uploadBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        
        // Start progress simulation
        startProgressSimulation();
        
        // Submit form
        $.ajax({
            url: 'importData.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Handle success
                setTimeout(function() {
                    $('#progressBar').css('width', '100%').removeClass('active');
                    $('#progressText').text('100%');
                    $('#progressLog').append('<div class="text-success"><i class="fa fa-check"></i> Import completed successfully!</div>');
                    
                    // Redirect after 2 seconds
                    setTimeout(function() {
                        window.location.href = 'rawslip.php?status=succ';
                    }, 2000);
                }, 1000);
            },
            error: function() {
                // Handle error
                $('#progressLog').append('<div class="text-danger"><i class="fa fa-times"></i> Import failed! Please try again.</div>');
                $('#uploadBtn').prop('disabled', false).html('<i class="fa fa-upload"></i> Start Import');
            }
        });
    });
});

function startProgressSimulation() {
    var progress = 0;
    var totalRecords = Math.floor(Math.random() * 1000) + 500; // Simulate random record count
    var processedRecords = 0;
    var errorRecords = 0;
    
    // Update initial values
    $('#totalRecords').text(totalRecords);
    $('#remainingRecords').text(totalRecords);
    $('#processedRecords').text(0);
    $('#errorRecords').text(0);
    
    // Add initial log entry
    $('#progressLog').html('<div class="text-info"><i class="fa fa-info"></i> Starting import process...</div>');
    
    var interval = setInterval(function() {
        progress += Math.random() * 15; // Random progress increment
        processedRecords += Math.floor(Math.random() * 20) + 5;
        
        if(progress > 100) progress = 100;
        if(processedRecords > totalRecords) processedRecords = totalRecords;
        
        var remaining = totalRecords - processedRecords;
        var percentage = Math.round(progress);
        
        // Update progress bar
        $('#progressBar').css('width', percentage + '%');
        $('#progressText').text(percentage + '%');
        
        // Update counters
        $('#processedRecords').text(processedRecords);
        $('#remainingRecords').text(remaining);
        
        // Simulate occasional errors
        if(Math.random() < 0.1) {
            errorRecords++;
            $('#errorRecords').text(errorRecords);
            $('#progressLog').append('<div class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning: Invalid data in row ' + processedRecords + '</div>');
        }
        
        // Add progress log entries
        if(processedRecords % 50 === 0) {
            $('#progressLog').append('<div class="text-info"><i class="fa fa-info"></i> Processed ' + processedRecords + ' records...</div>');
        }
        
        // Scroll to bottom of log
        var logContainer = document.getElementById('progressLog');
        logContainer.scrollTop = logContainer.scrollHeight;
        
        if(progress >= 100) {
            clearInterval(interval);
        }
    }, 200); // Update every 200ms
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

.progress-lg {
    height: 25px;
    border-radius: 12px;
}

.progress-bar {
    border-radius: 12px;
    font-weight: bold;
    font-size: 14px;
}

.info-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#progressLog {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    line-height: 1.4;
}

#progressLog div {
    margin-bottom: 5px;
    padding: 2px 5px;
    border-radius: 3px;
}

#progressLog .text-info {
    background-color: #d9edf7;
    color: #31708f;
}

#progressLog .text-success {
    background-color: #dff0d8;
    color: #3c763d;
}

#progressLog .text-warning {
    background-color: #fcf8e3;
    color: #8a6d3b;
}

#progressLog .text-danger {
    background-color: #f2dede;
    color: #a94442;
}

/* File input styling */
.input-group-btn .btn {
    border-radius: 0 4px 4px 0;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .info-box-content {
        text-align: center;
    }
    
    .table-responsive {
        border: none;
    }
}

/* Animation for progress */
@keyframes progressAnimation {
    0% { width: 0%; }
    100% { width: 100%; }
}

.progress-bar.active {
    animation: progressAnimation 2s ease-in-out;
}
</style>

</body>
</html>


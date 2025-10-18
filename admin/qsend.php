<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<style>
/* Modern UI Styles */
.send-form-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

/* Search Box Styles */
#centerSearch {
    transition: all 0.3s ease;
    border: 2px solid rgba(102, 126, 234, 0.5);
}

#centerSearch:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    transform: translateY(-1px);
}

.center-item {
    transition: all 0.3s ease;
}

.center-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.send-form-container h3 {
    color: white;
    font-weight: 600;
    margin-bottom: 30px;
    text-align: center;
}

.form-group-custom {
    margin-bottom: 25px;
}

.form-group-custom label {
    color: white;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

.form-control-custom {
    border-radius: 8px;
    border: 2px solid rgba(255,255,255,0.3);
    padding: 12px 15px;
    background: rgba(255,255,255,0.95);
    transition: all 0.3s ease;
    color: #333;
    font-size: 14px;
    line-height: 1.4;
}

.form-control-custom:focus {
    border-color: #fff;
    box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.25);
    background: white;
    color: #333;
}

.form-control-custom option {
    color: #333;
    background: white;
    padding: 8px 12px;
}

.btn-send-questions {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
    padding: 15px 40px;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-send-questions:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

.btn-send-questions:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Loader Styles */
.loader-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.loader-content {
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: 20px;
    max-width: 500px;
    width: 90%;
}

.spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #667eea;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-status {
    color: #333;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.loader-details {
    color: #666;
    font-size: 14px;
}

.progress-container {
    margin-top: 20px;
    display: block;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.progress-header h4 {
    margin: 0;
    color: #495057;
    font-size: 18px;
    font-weight: 600;
}

.progress-stats {
    color: #6c757d;
    font-size: 14px;
    font-weight: 500;
}

.progress-bar-container {
    position: relative;
    margin-bottom: 20px;
}

.progress-bar {
    width: 100%;
    height: 12px;
    background-color: #e9ecef;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
    border-radius: 6px;
    transition: width 0.5s ease;
    width: 0%;
}

.progress-text {
    position: absolute;
    top: -25px;
    right: 0;
    color: #495057;
    font-weight: 600;
    font-size: 14px;
}

.study-centres-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: white;
}

.study-centre-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #f1f3f4;
    transition: all 0.3s ease;
}

.study-centre-item:last-child {
    border-bottom: none;
}

.study-centre-item.processing {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
}

.study-centre-item.completed {
    background: #e8f5e9;
    border-left: 4px solid #4caf50;
}

.study-centre-item.failed {
    background: #ffebee;
    border-left: 4px solid #f44336;
}

.study-centre-status {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 12px;
    font-weight: bold;
}

.study-centre-status.pending {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #dee2e6;
}

.study-centre-status.processing {
    background: #2196f3;
    color: white;
    animation: pulse 1.5s infinite;
}

.study-centre-status.completed {
    background: #4caf50;
    color: white;
}

.study-centre-status.failed {
    background: #f44336;
    color: white;
}

.study-centre-info {
    flex: 1;
}

.study-centre-name {
    font-weight: 600;
    color: #495057;
    margin-bottom: 2px;
}

.study-centre-details {
    font-size: 12px;
    color: #6c757d;
}

.current-processing {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #2196f3;
}

.processing-item {
    display: flex;
    align-items: center;
}

.processing-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #2196f3;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 12px;
}

.processing-details {
    flex: 1;
}

.processing-name {
    font-weight: 600;
    color: #495057;
    margin-bottom: 2px;
}

.processing-status {
    font-size: 12px;
    color: #6c757d;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Results Modal Styles */
.modal-header-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    border-radius: 10px 10px 0 0;
}

.modal-header-error {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    color: white;
    border-radius: 10px 10px 0 0;
}

.results-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.results-summary h4 {
    color: #333;
    font-weight: 600;
    margin-bottom: 15px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    font-weight: 500;
    color: #666;
}

.summary-value {
    font-weight: 600;
    color: #333;
}

.recipient-list {
    max-height: 400px;
    overflow-y: auto;
}

.recipient-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.recipient-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.recipient-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.recipient-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    margin-right: 15px;
}

.recipient-info {
    flex: 1;
}

.recipient-name {
    font-weight: 600;
    color: #333;
    margin: 0;
}

.recipient-email {
    color: #666;
    font-size: 14px;
    margin: 0;
}

.recipient-details {
    padding-left: 55px;
}

.detail-row {
    padding: 5px 0;
    font-size: 14px;
}

.detail-label {
    color: #666;
    display: inline-block;
    min-width: 100px;
}

.detail-value {
    color: #333;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-success {
    background: #d4edda;
    color: #155724;
}

.status-error {
    background: #f8d7da;
    color: #721c24;
}

.modal-footer-custom {
    border-top: 2px solid #dee2e6;
    padding: 20px;
}
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Send POP Exam Questions
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Questions</li>
        <li class="active">Scheduled Examination Questions</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <?php $connection = require "connection.php"; 
$query = "select student_registrations.exam_session from `student_registrations` group by `student_registrations`.`exam_session`";
$query_data = mysqli_query($connection,$query);

$day = "select student_registrations.id,student_registrations.exam_day from `student_registrations` group by `student_registrations`.`exam_day` order by `id` asc";
$day_data = mysqli_query($connection,$day);
          ?>

          <div class="send-form-container">
            <h3><i class="fa fa-paper-plane"></i> Exam Questions Distribution</h3>
            
            <form id="sendQuestionsForm">
              <!-- Exam Type Selection -->
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group-custom">
                    <label><i class="fa fa-check-square"></i> Exam Type</label>
                    <select class="form-control form-control-custom" name="exam_type" id="exam_type" required>
                      <option value="normal">Normal Exam (All Centers)</option>
                      <option value="makeup">Makeup Exam (Specific Centers)</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group-custom">
                    <label><i class="fa fa-calendar"></i> Select Exam Session</label>
                    <select class="form-control form-control-custom" name="exam_session" id="exam_session" required>
                      <option value="">-- Select Session --</option>
	<?php
                      if(mysqli_num_rows($query_data) > 0) {
                        while($obj = mysqli_fetch_assoc($query_data)) { 
					    echo '<option value="'. $obj['exam_session'] . '">'. $obj['exam_session'] . '</option>';
					}
				}
			    ?>
			  </select>
			</div>
			</div>
                
			<div class="col-md-6">
                  <div class="form-group-custom">
                    <label><i class="fa fa-clock-o"></i> Select Exam Day</label>
                    <select class="form-control form-control-custom" name="exam_day" id="exam_day" required>
                      <option value="">-- Select Day --</option>
                      <?php 
                      if(mysqli_num_rows($day_data) > 0) {
                        while($obj = mysqli_fetch_assoc($day_data)) { 
					    echo '<option value="'. $obj['exam_day'] . '">'. $obj['exam_day'] . '</option>';
					}
				}
			    ?>
			  </select>
			</div>
			</div>
              </div>
              
              <!-- Makeup Exam Options (Hidden by default) -->
              <div id="makeupOptions" style="display: none;">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group-custom">
                      <label><i class="fa fa-folder"></i> Question Folder</label>
                      <select class="form-control form-control-custom" name="question_folder" id="question_folder">
                        <option value="">-- Select Folder --</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group-custom">
                      <label><i class="fa fa-refresh"></i> Refresh Folders</label>
                      <button type="button" class="btn btn-default btn-block" onclick="loadQuestionFolders()" style="border-radius: 8px; padding: 12px;">
                        <i class="fa fa-refresh"></i> Reload Folders
                      </button>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group-custom">
                      <label><i class="fa fa-building"></i> Select Study Centers for Makeup Exam</label>
                      
                      <!-- Search Box -->
                      <div style="margin-bottom: 10px;">
                        <input type="text" id="centerSearch" class="form-control form-control-custom" placeholder="🔍 Search centers by name, director, or email..." style="background: rgba(255,255,255,0.95) !important;">
                      </div>
                      
                      <!-- Centers List -->
                      <div style="background: rgba(255,255,255,0.95); border-radius: 8px; padding: 15px; max-height: 300px; overflow-y: auto;">
                        <div id="centersList">
                          <p style="color: #666; text-align: center;">Select exam session and day to load centers</p>
                        </div>
                      </div>
                      
                      <!-- Search Results Counter -->
                      <div id="searchResultsCounter" style="color: white; margin-top: 8px; font-size: 12px; display: none;">
                        <i class="fa fa-info-circle"></i> <span id="visibleCount">0</span> of <span id="totalCount">0</span> centers shown
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group-custom">
                    <label><i class="fa fa-envelope"></i> Enter Email Subject</label>
                    <input class="form-control form-control-custom" type="text" name="subject" id="subject" 
                           placeholder="e.g., POP Examination Questions - Session 2024/2025" required>
                  </div>
			</div>
			</div>
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group-custom">
                    <label><i class="fa fa-edit"></i> Enter Email Body (Optional)</label>
                    <textarea class="form-control form-control-custom" name="body" id="body" rows="4" 
                              placeholder="Enter additional message to be included in the email..."></textarea>
                  </div>
                </div>
			</div>
            
              <div class="row">
                <div class="col-md-12 text-center">
                  <button class="btn btn-send-questions" type="submit" id="sendBtn">
                    <i class="fa fa-paper-plane"></i> <span id="sendBtnText">Send Questions to All Centers</span>
                  </button>
                </div>
			 </div>
			</form>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>

</div>

<!-- Loader Overlay -->
<div class="loader-overlay" id="loaderOverlay">
  <div class="loader-content">
    <div class="spinner"></div>
    <div class="loader-status" id="loaderStatus">Preparing to send questions...</div>
    <div class="loader-details" id="loaderDetails">Please wait while we process your request</div>
    
    <!-- Progress Container -->
    <div class="progress-container" id="progressContainer">
      <div class="progress-header">
        <h4>📤 Sending Questions Progress</h4>
        <div class="progress-stats">
          <span id="progressStats">0 of 0 study centres</span>
        </div>
      </div>
      
      <div class="progress-bar-container">
        <div class="progress-bar">
          <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-text" id="progressText">0%</div>
      </div>
      
      <!-- Study Centres List -->
      <div class="study-centres-list" id="studyCentresList">
        <!-- Study centres will be added dynamically -->
      </div>
      
      <!-- Current Processing -->
      <div class="current-processing" id="currentProcessing" style="display: none;">
        <div class="processing-item">
          <div class="processing-spinner"></div>
          <div class="processing-details">
            <div class="processing-name" id="processingName">Processing...</div>
            <div class="processing-status" id="processingStatus">Initializing...</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Results Modal -->
<div class="modal fade" id="resultsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 15px; overflow: hidden;">
      <div class="modal-header modal-header-success" id="modalHeader">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalTitle">
          <i class="fa fa-check-circle"></i> Questions Sent Successfully
        </h4>
      </div>
      <div class="modal-body" style="padding: 30px;">
        <div class="results-summary" id="resultsSummary">
          <!-- Summary will be populated by JavaScript -->
        </div>
        
        <h4 style="margin-bottom: 20px; color: #333;"><i class="fa fa-users"></i> Recipient Details</h4>
        <div class="recipient-list" id="recipientList">
          <!-- Recipients will be populated by JavaScript -->
        </div>
      </div>
      <div class="modal-footer modal-footer-custom">
        <button type="button" class="btn btn-primary" data-dismiss="modal">
          <i class="fa fa-times"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/scripts.php'; ?>

<script>
$(document).ready(function(){
  // Load question folders on page load
  loadQuestionFolders();
  
  // Handle exam type change
  $('#exam_type').change(function(){
    var examType = $(this).val();
    
    if(examType === 'makeup') {
      $('#makeupOptions').slideDown();
      $('#sendBtnText').text('Send Questions to Selected Centers');
      
      // Load centers if session and day are selected
      var examSession = $('#exam_session').val();
      var examDay = $('#exam_day').val();
      if(examSession && examDay) {
        loadCentersForMakeup(examSession, examDay);
      }
    } else {
      $('#makeupOptions').slideUp();
      $('#sendBtnText').text('Send Questions to All Centers');
    }
  });
  
  // Load centers when session or day changes (for makeup)
  $('#exam_session, #exam_day').change(function(){
    var examType = $('#exam_type').val();
    if(examType === 'makeup') {
      var examSession = $('#exam_session').val();
      var examDay = $('#exam_day').val();
      if(examSession && examDay) {
        loadCentersForMakeup(examSession, examDay);
      }
    }
  });
  
  // Handle form submission
  $('#sendQuestionsForm').on('submit', function(e){
    e.preventDefault();
    
    // Validate form
    var examType = $('#exam_type').val();
    var examSession = $('#exam_session').val();
    var examDay = $('#exam_day').val();
    var subject = $('#subject').val();
    
    if(!examSession || !examDay || !subject) {
      alert('Please fill in all required fields');
      return;
    }
    
    // Additional validation for makeup exam
    if(examType === 'makeup') {
      var questionFolder = $('#question_folder').val();
      console.log('Selected question folder:', questionFolder);
      if(!questionFolder) {
        alert('Please select a question folder for makeup exam');
        return;
      }
      
      var selectedCenters = [];
      $('input[name="selected_centers[]"]:checked').each(function(){
        selectedCenters.push($(this).val());
      });
      
      if(selectedCenters.length === 0) {
        alert('Please select at least one study center for makeup exam');
        return;
      }
    }
    
    // Show loader with progress UI
    $('#loaderOverlay').css('display', 'flex');
    $('#sendBtn').prop('disabled', true);
    
    // Initialize progress UI
    initializeProgressUI();
    
    // Get form data
    var formData = $(this).serialize();
    console.log('Form data being sent:', formData);
    
    // Determine which AJAX handler to use
    var ajaxUrl = (examType === 'makeup') ? 'qsend_ajax_makeup.php' : 'qsend_ajax_simplified.php';
    console.log('Using AJAX URL:', ajaxUrl);
    
    // Send AJAX request
    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      data: formData,
      dataType: 'json',
      xhr: function() {
        var xhr = new window.XMLHttpRequest();
        return xhr;
      },
      success: function(response) {
        console.log('Success response:', response);
        
        if(response.success) {
          // Hide loader immediately and show results
          $('#loaderOverlay').css('display', 'none');
          $('#sendBtn').prop('disabled', false);
          // Show success modal immediately
          showResultsModal(response);
        } else {
          // Hide loader immediately on error
          $('#loaderOverlay').css('display', 'none');
          $('#sendBtn').prop('disabled', false);
          // Show error
          alert('Error: ' + (response.message || 'Failed to send questions'));
        }
      },
      error: function(xhr, status, error) {
        console.log('Error details:', {
          status: status,
          error: error,
          responseText: xhr.responseText,
          statusText: xhr.statusText
        });
        // Hide loader
        $('#loaderOverlay').css('display', 'none');
        $('#sendBtn').prop('disabled', false);
        
        alert('An error occurred: ' + error + '\n\nResponse: ' + xhr.responseText.substring(0, 200));
      }
    });
  });
  
  function showResultsModal(response) {
    // Update modal header
    if(response.success) {
      $('#modalHeader').removeClass('modal-header-error').addClass('modal-header-success');
      $('#modalTitle').html('<i class="fa fa-check-circle"></i> Questions Sent Successfully');
    } else {
      $('#modalHeader').removeClass('modal-header-success').addClass('modal-header-error');
      $('#modalTitle').html('<i class="fa fa-exclamation-circle"></i> Error Sending Questions');
    }
    
    // Populate summary
    var summaryHtml = '<h4><i class="fa fa-bar-chart"></i> Summary</h4>';
    summaryHtml += '<div class="summary-item">';
    summaryHtml += '<span class="summary-label">Exam Session:</span>';
    summaryHtml += '<span class="summary-value">' + response.exam_session + '</span>';
    summaryHtml += '</div>';
    summaryHtml += '<div class="summary-item">';
    summaryHtml += '<span class="summary-label">Exam Day:</span>';
    summaryHtml += '<span class="summary-value">' + response.exam_day + '</span>';
    summaryHtml += '</div>';
    summaryHtml += '<div class="summary-item">';
    summaryHtml += '<span class="summary-label">Total Study Centers:</span>';
    summaryHtml += '<span class="summary-value">' + response.total_centers + '</span>';
    summaryHtml += '</div>';
    summaryHtml += '<div class="summary-item">';
    summaryHtml += '<span class="summary-label">Successfully Sent:</span>';
    summaryHtml += '<span class="summary-value" style="color: #28a745;">' + response.sent_count + '</span>';
    summaryHtml += '</div>';
    if(response.failed_count > 0) {
      summaryHtml += '<div class="summary-item">';
      summaryHtml += '<span class="summary-label">Failed:</span>';
      summaryHtml += '<span class="summary-value" style="color: #dc3545;">' + response.failed_count + '</span>';
      summaryHtml += '</div>';
    }
    summaryHtml += '<div class="summary-item">';
    summaryHtml += '<span class="summary-label">Sent By:</span>';
    summaryHtml += '<span class="summary-value">' + response.sent_by + '</span>';
    summaryHtml += '</div>';
    $('#resultsSummary').html(summaryHtml);
    
    // Populate recipient list
    var recipientHtml = '';
    if(response.recipients && response.recipients.length > 0) {
      response.recipients.forEach(function(recipient, index) {
        recipientHtml += '<div class="recipient-card">';
        recipientHtml += '<div class="recipient-header">';
        recipientHtml += '<div class="recipient-icon">' + (index + 1) + '</div>';
        recipientHtml += '<div class="recipient-info">';
        recipientHtml += '<p class="recipient-name">' + recipient.study_center + '</p>';
        recipientHtml += '<p class="recipient-email"><i class="fa fa-envelope"></i> ' + recipient.email + '</p>';
        recipientHtml += '</div>';
        recipientHtml += '<span class="status-badge status-' + (recipient.status === 'sent' ? 'success' : 'error') + '">';
        recipientHtml += recipient.status === 'sent' ? 'Sent' : 'Failed';
        recipientHtml += '</span>';
        recipientHtml += '</div>';
        recipientHtml += '<div class="recipient-details">';
        recipientHtml += '<div class="detail-row">';
        recipientHtml += '<span class="detail-label">Center Code:</span>';
        recipientHtml += '<span class="detail-value">' + recipient.center_code + '</span>';
        recipientHtml += '</div>';
        recipientHtml += '<div class="detail-row">';
        recipientHtml += '<span class="detail-label">Director:</span>';
        recipientHtml += '<span class="detail-value">' + recipient.director + '</span>';
        recipientHtml += '</div>';
        if(recipient.password) {
          recipientHtml += '<div class="detail-row">';
          recipientHtml += '<span class="detail-label">Password:</span>';
          recipientHtml += '<span class="detail-value"><code>' + recipient.password + '</code></span>';
          recipientHtml += '</div>';
        }
        if(recipient.error_message) {
          recipientHtml += '<div class="detail-row">';
          recipientHtml += '<span class="detail-label">Error:</span>';
          recipientHtml += '<span class="detail-value" style="color: #dc3545;">' + recipient.error_message + '</span>';
          recipientHtml += '</div>';
        }
        recipientHtml += '</div>';
        recipientHtml += '</div>';
      });
    } else {
      recipientHtml = '<p class="text-center">No recipients found.</p>';
    }
    $('#recipientList').html(recipientHtml);
    
    // Show modal
    $('#resultsModal').modal('show');
  }
  
  // Progress UI Functions
  function initializeProgressUI() {
    // Reset progress
    $('#progressFill').css('width', '0%');
    $('#progressText').text('0%');
    $('#progressStats').text('0 of 0 study centres');
    $('#studyCentresList').empty();
    $('#currentProcessing').hide();
    
    // Reset progress bar
    $('#progressBar').css('width', '0%');
    $('#progressBar').removeClass('completed');
    
    // Show initial status
    $('#loaderStatus').text('Initializing...');
    $('#loaderDetails').text('Preparing to send questions to study centres');
  }
  
  function updateProgressUI(percentage, status, details) {
    // Update progress bar
    $('#progressFill').css('width', percentage + '%');
    $('#progressText').text(percentage + '%');
    
    // Update status
    $('#loaderStatus').text(status);
    $('#loaderDetails').text(details);
  }
  
  function addStudyCentreToList(name, email, director, status = 'pending') {
    var statusIcon = getStatusIcon(status);
    var statusClass = getStatusClass(status);
    
    var item = `
      <div class="study-centre-item ${statusClass}" data-centre="${name}">
        <div class="study-centre-status ${statusClass}">${statusIcon}</div>
        <div class="study-centre-info">
          <div class="study-centre-name">${name}</div>
          <div class="study-centre-details">${director} • ${email}</div>
        </div>
      </div>
    `;
    
    $('#studyCentresList').append(item);
  }
  
  function updateStudyCentreStatus(name, status) {
    var $item = $(`[data-centre="${name}"]`);
    var statusIcon = getStatusIcon(status);
    var statusClass = getStatusClass(status);
    
    $item.removeClass('pending processing completed failed').addClass(statusClass);
    $item.find('.study-centre-status').removeClass('pending processing completed failed').addClass(statusClass).text(statusIcon);
  }
  
  function updateCurrentProcessing(name, status) {
    $('#processingName').text(name);
    $('#processingStatus').text(status);
    $('#currentProcessing').show();
  }
  
  function hideCurrentProcessing() {
    $('#currentProcessing').hide();
  }
  
  function getStatusIcon(status) {
    switch(status) {
      case 'pending': return '⏳';
      case 'processing': return '🔄';
      case 'completed': return '✅';
      case 'failed': return '❌';
      default: return '⏳';
    }
  }
  
  function getStatusClass(status) {
    switch(status) {
      case 'pending': return 'pending';
      case 'processing': return 'processing';
      case 'completed': return 'completed';
      case 'failed': return 'failed';
      default: return 'pending';
    }
  }
  
  function displayRealProgress(response) {
    // Display real progress data from server response
    console.log('Displaying real progress:', response);
    
    if (response.recipients && response.recipients.length > 0) {
      var total = response.recipients.length;
      var sent = response.sent_count || 0;
      var failed = response.failed_count || 0;
      
      // Clear existing list
      $('#studyCentresList').empty();
      
      // Add all study centres to the list with their actual status
      response.recipients.forEach(function(recipient) {
        var status = recipient.status || 'completed'; // Default to completed since processing is done
        addStudyCentreToList(
          recipient.study_center || 'Unknown Center',
          recipient.email || 'No email',
          recipient.director || 'Unknown Director',
          status
        );
      });
      
      // Update progress stats - show completed
      $('#progressStats').text(`${total} of ${total} study centres`);
      
      // Update progress bar to 100%
      $('#progressBar').css('width', '100%');
      $('#progressBar').addClass('completed');
      
      // Update status to completed
      updateLoaderStatus('Completed', `Successfully sent to ${sent} centers, ${failed} failed`);
    } else {
      // No recipients data
      updateLoaderStatus('Completed', 'No study centers to process');
      $('#progressStats').text('0 of 0 study centres');
    }
  }
  
  // Function to load question folders
  window.loadQuestionFolders = function() {
    console.log('Loading question folders...');
    $.ajax({
      url: 'ajax_get_folders.php',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        console.log('Folders response:', response);
        if(response.success && response.folders) {
          var html = '<option value="">-- Select Folder --</option>';
          response.folders.forEach(function(folder) {
            html += '<option value="' + folder.name + '">' + folder.display_name + ' (' + folder.count + ' files)</option>';
          });
          $('#question_folder').html(html);
          console.log('Folders loaded successfully');
        } else {
          console.log('Failed to load folders:', response.message);
        }
      },
      error: function(xhr, status, error) {
        console.log('AJAX error loading folders:', status, error, xhr.responseText);
      }
    });
  }
  
  // Function to load centers for makeup exam
  function loadCentersForMakeup(examSession, examDay) {
    console.log('Loading centers for makeup exam:', examSession, examDay);
    $.ajax({
      url: 'ajax_get_centers.php',
      type: 'POST',
      data: {
        exam_session: examSession,
        exam_day: examDay
      },
      dataType: 'json',
      success: function(response) {
        console.log('Centers response:', response);
        if(response.success && response.centers) {
          renderCentersList(response.centers);
        } else {
          $('#centersList').html('<p style="color: #666; text-align: center;">No centers found for this session and day</p>');
        }
      },
      error: function(xhr, status, error) {
        console.log('AJAX error loading centers:', status, error, xhr.responseText);
        $('#centersList').html('<p style="color: #dc3545; text-align: center;">Failed to load centers</p>');
      }
    });
  }
  
  // Function to load question folders from DEASemester
  function loadQuestionFolders() {
    console.log('Loading question folders...');
    $.ajax({
      url: 'ajax_get_folders.php',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        console.log('Folders response:', response);
        if(response.success && response.folders) {
          var html = '<option value="">-- Select Folder --</option>';
          response.folders.forEach(function(folder) {
            html += '<option value="' + folder.name + '">' + folder.name + ' (' + folder.file_count + ' files)</option>';
          });
          $('#question_folder').html(html);
        } else {
          $('#question_folder').html('<option value="">No folders found</option>');
        }
      },
      error: function(xhr, status, error) {
        console.log('AJAX error loading folders:', status, error, xhr.responseText);
        $('#question_folder').html('<option value="">Error loading folders</option>');
      }
    });
  }
  
  // Function to load centers for makeup exam
  function loadCentersForMakeup(examSession, examDay) {
    console.log('Loading centers for makeup exam:', examSession, examDay);
    $('#centersList').html('<p style="color: #666; text-align: center;"><i class="fa fa-spinner fa-spin"></i> Loading centers...</p>');
    
    $.ajax({
      url: 'ajax_get_centers.php',
      type: 'POST',
      data: {
        exam_session: examSession,
        exam_day: examDay
      },
      dataType: 'json',
      success: function(response) {
        console.log('Centers response:', response);
        if(response.success && response.centers && response.centers.length > 0) {
          renderCentersList(response.centers);
        } else {
          $('#centersList').html('<p style="color: #666; text-align: center;">No centers found for this session and day</p>');
        }
      },
      error: function(xhr, status, error) {
        console.log('AJAX error loading centers:', status, error, xhr.responseText);
        $('#centersList').html('<p style="color: #d9534f; text-align: center;">Error loading centers. Please try again.</p>');
      }
    });
  }
  
  // Function to render centers list
  function renderCentersList(centers) {
    var html = '<div style="margin-bottom: 15px;">';
    html += '<label style="cursor: pointer; color: #667eea; font-weight: 600;">';
    html += '<input type="checkbox" id="selectAllCenters" style="margin-right: 8px;"> Select All Centers';
    html += '</label>';
    html += '</div>';
    
    centers.forEach(function(center) {
      html += '<div class="center-item" data-center-name="' + center.name.toLowerCase() + '" data-center-director="' + center.director.toLowerCase() + '" data-center-email="' + center.email.toLowerCase() + '" style="background: white; padding: 12px; margin-bottom: 8px; border-radius: 6px; border: 1px solid #e0e7ff;">';
      html += '<label style="margin: 0; cursor: pointer; display: flex; align-items: center;">';
      html += '<input type="checkbox" name="selected_centers[]" value="' + center.code + '" style="margin-right: 12px;">';
      html += '<div>';
      html += '<div style="font-weight: 600; color: #333;">' + center.name + '</div>';
      html += '<div style="font-size: 12px; color: #666;">' + center.director + ' • ' + center.email + '</div>';
      html += '</div>';
      html += '</label>';
      html += '</div>';
    });
    
    $('#centersList').html(html);
    
    // Update counter
    updateSearchCounter();
    
    // Handle select all
    $('#selectAllCenters').change(function(){
      $('input[name="selected_centers[]"]:visible').each(function(){
        $(this).prop('checked', $('#selectAllCenters').is(':checked'));
      });
    });
    
    // Enable search functionality
    setupCenterSearch();
  }
  
  // Function to setup center search
  function setupCenterSearch() {
    $('#centerSearch').off('keyup').on('keyup', function(){
      var searchTerm = $(this).val().toLowerCase().trim();
      
      if(searchTerm === '') {
        // Show all centers
        $('.center-item').show();
      } else {
        // Filter centers
        $('.center-item').each(function(){
          var centerName = $(this).data('center-name');
          var centerDirector = $(this).data('center-director');
          var centerEmail = $(this).data('center-email');
          
          if(centerName.includes(searchTerm) || centerDirector.includes(searchTerm) || centerEmail.includes(searchTerm)) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      }
      
      // Update counter
      updateSearchCounter();
    });
  }
  
  // Function to update search counter
  function updateSearchCounter() {
    var totalCenters = $('.center-item').length;
    var visibleCenters = $('.center-item:visible').length;
    
    if(totalCenters > 0) {
      $('#totalCount').text(totalCenters);
      $('#visibleCount').text(visibleCenters);
      $('#searchResultsCounter').show();
    } else {
      $('#searchResultsCounter').hide();
    }
  }
});
</script>

</body>
</html>


<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<style>
/* Modern Professional UI */
.upload-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.upload-container h3 {
    color: white;
    font-weight: 600;
    margin-bottom: 30px;
    text-align: center;
}

.upload-section {
    background: rgba(255,255,255,0.95);
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 20px;
}

.upload-section h4 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 20px;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
}

.form-group-custom {
    margin-bottom: 25px;
}

.form-group-custom label {
    color: #333;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

.form-control-custom {
    border-radius: 8px;
    border: 2px solid rgba(102, 126, 234, 0.3);
    padding: 12px 15px;
    background: white !important;
    transition: all 0.3s ease;
    color: #333 !important;
    font-size: 14px;
    line-height: 1.4;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px;
}

.form-control-custom:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white !important;
    color: #333 !important;
    outline: none;
}

.form-control-custom option {
    color: #333 !important;
    background: white !important;
    padding: 8px 12px;
    font-size: 14px;
}

.form-control-custom select {
    color: #333 !important;
    background: white !important;
}

/* Force text visibility */
select.form-control-custom {
    color: #333 !important;
    background: white !important;
}

select.form-control-custom {
    color: #333 !important;
    background: white !important;
}

.dropzone-area {
    border: 3px dashed #667eea;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    background: #f8f9ff;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.dropzone-area:hover {
    background: #eef1ff;
    border-color: #764ba2;
}

.dropzone-area.dragover {
    background: #e0e7ff;
    border-color: #764ba2;
    border-style: solid;
}

.dropzone-icon {
    font-size: 48px;
    color: #667eea;
    margin-bottom: 15px;
}

.dropzone-text {
    color: #333;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.dropzone-subtext {
    color: #666;
    font-size: 14px;
}

.btn-upload {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 15px 40px;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    color: white;
}

.btn-upload:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.file-list {
    margin-top: 20px;
    max-height: 300px;
    overflow-y: auto;
}

.file-item {
    background: white;
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.file-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.file-icon {
    font-size: 24px;
    margin-right: 15px;
    color: #667eea;
}

.file-details {
    flex: 1;
}

.file-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
}

.file-size {
    font-size: 12px;
    color: #666;
}

.file-remove {
    background: #ef4444;
    border: none;
    color: white;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.file-remove:hover {
    background: #dc2626;
}

.progress-container {
    margin-top: 20px;
    display: none;
}

.progress-bar-wrapper {
    background: #e0e7ff;
    border-radius: 10px;
    height: 30px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-bar-fill {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    height: 100%;
    width: 0%;
    transition: width 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.progress-text {
    color: #333;
    font-size: 14px;
    text-align: center;
}

.alert-custom {
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border: none;
}

.alert-success-custom {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.alert-error-custom {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.folders-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.folders-section h4 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 20px;
}

.folder-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.folder-card {
    background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%);
    border: 2px solid #e0e7ff;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.folder-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    transform: translateY(-2px);
}

.folder-icon {
    font-size: 48px;
    color: #667eea;
    margin-bottom: 10px;
}

.folder-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.folder-count {
    font-size: 12px;
    color: #666;
}
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <h1>
        <i class="fa fa-upload"></i> Manage Examination Questions
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Questions</li>
        <li class="active">Manage Questions</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <!-- Alert Messages -->
          <div id="alertContainer"></div>
          
          <div class="upload-container">
            <h3><i class="fa fa-cloud-upload"></i> Upload Examination Questions</h3>
            
            <!-- Upload Type Selection -->
            <div class="upload-section">
              <h4><i class="fa fa-folder"></i> Upload Destination</h4>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group-custom">
                    <label>Upload Type</label>
                    <select class="form-control form-control-custom" id="uploadType">
                      <option value="default">Default Folder (DEASemester)</option>
                      <option value="makeup">Makeup Exam (Create New Folder)</option>
                    </select>
                  </div>
                </div>
                
                <div class="col-md-6" id="folderNameGroup" style="display: none;">
                  <div class="form-group-custom">
                    <label>Folder Name <small>(e.g., MakeupExam_Jan2024, Resit_Biology)</small></label>
                    <input type="text" class="form-control form-control-custom" id="folderName" placeholder="Enter folder name">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Upload Method Selection -->
            <div class="upload-section">
              <h4><i class="fa fa-file"></i> Upload Method</h4>
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group-custom">
                    <label>Choose Upload Method</label>
                    <select class="form-control form-control-custom" id="uploadMethod">
                      <option value="zip" selected>Upload ZIP File (Recommended for bulk upload)</option>
                      <option value="pdf">Upload Individual PDF Files</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <!-- ZIP Upload -->
              <div id="zipUploadArea">
                <div class="dropzone-area" id="dropzoneZip">
                  <div class="dropzone-icon">📦</div>
                  <div class="dropzone-text">Drop ZIP file here or click to browse</div>
                  <div class="dropzone-subtext">Upload a ZIP file containing PDF question files</div>
                  <input type="file" id="zipFileInput" accept=".zip" style="display: none;">
                </div>
                
                <div id="zipFileInfo" style="display: none;" class="file-list">
                  <div class="file-item" id="zipFileItem">
                    <div class="file-info">
                      <div class="file-icon">📦</div>
                      <div class="file-details">
                        <div class="file-name" id="zipFileName"></div>
                        <div class="file-size" id="zipFileSize"></div>
                      </div>
                    </div>
                    <button class="file-remove" onclick="removeZipFile()">Remove</button>
                  </div>
                </div>
              </div>
              
              <!-- PDF Upload -->
              <div id="pdfUploadArea" style="display: none;">
                <div class="dropzone-area" id="dropzonePdf">
                  <div class="dropzone-icon">📄</div>
                  <div class="dropzone-text">Drop PDF files here or click to browse</div>
                  <div class="dropzone-subtext">You can select multiple PDF files at once</div>
                  <input type="file" id="pdfFileInput" accept=".pdf" multiple style="display: none;">
                </div>
                
                <div id="pdfFileList" class="file-list"></div>
              </div>
              
              <!-- Progress -->
              <div class="progress-container" id="progressContainer">
                <div class="progress-bar-wrapper">
                  <div class="progress-bar-fill" id="progressBarFill">0%</div>
                </div>
                <div class="progress-text" id="progressText">Uploading...</div>
              </div>
              
              <!-- Upload Button -->
              <div class="text-center" style="margin-top: 30px;">
                <button class="btn btn-upload" id="uploadBtn" onclick="uploadFiles()">
                  <i class="fa fa-cloud-upload"></i> Upload Files
                </button>
              </div>
            </div>
          </div>
          
          <!-- Existing Folders -->
          <div class="folders-section">
            <h4><i class="fa fa-folder-open"></i> Existing Question Folders</h4>
            <div class="folder-list" id="folderList">
              <div class="folder-card" onclick="viewFolder('DEASemester')">
                <div class="folder-icon">📁</div>
                <div class="folder-name">DEASemester</div>
                <div class="folder-count">Default folder</div>
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

<script>
$(document).ready(function(){
  // Load existing folders
  loadFolders();
  
  // Upload type change
  $('#uploadType').change(function(){
    if($(this).val() === 'makeup') {
      $('#folderNameGroup').show();
    } else {
      $('#folderNameGroup').hide();
      $('#folderName').val('');
    }
  });
  
  // Upload method change
  $('#uploadMethod').change(function(){
    console.log('Upload method changed to:', $(this).val());
    if($(this).val() === 'zip') {
      $('#zipUploadArea').show();
      $('#pdfUploadArea').hide();
    } else {
      $('#zipUploadArea').hide();
      $('#pdfUploadArea').show();
    }
  });
  
  // Initialize upload method on page load
  $('#uploadMethod').trigger('change');
  
  // Dropzone for ZIP
  var dropzoneZip = $('#dropzoneZip');
  dropzoneZip.on('click', function(){
    $('#zipFileInput').click();
  });
  
  dropzoneZip.on('dragover', function(e){
    e.preventDefault();
    e.stopPropagation();
    $(this).addClass('dragover');
  });
  
  dropzoneZip.on('dragleave', function(e){
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
  });
  
  dropzoneZip.on('drop', function(e){
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
    
    var files = e.originalEvent.dataTransfer.files;
    if(files.length > 0 && files[0].name.endsWith('.zip')) {
      handleZipFile(files[0]);
    } else {
      showAlert('Please drop a ZIP file', 'error');
    }
  });
  
  $('#zipFileInput').change(function(){
    console.log('ZIP file input changed:', this.files);
    if(this.files.length > 0) {
      handleZipFile(this.files[0]);
    }
  });
  
  // Dropzone for PDF
  var dropzonePdf = $('#dropzonePdf');
  dropzonePdf.on('click', function(){
    $('#pdfFileInput').click();
  });
  
  dropzonePdf.on('dragover', function(e){
    e.preventDefault();
    e.stopPropagation();
    $(this).addClass('dragover');
  });
  
  dropzonePdf.on('dragleave', function(e){
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
  });
  
  dropzonePdf.on('drop', function(e){
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
    
    var files = e.originalEvent.dataTransfer.files;
    handlePdfFiles(files);
  });
  
  $('#pdfFileInput').change(function(){
    console.log('PDF file input changed:', this.files);
    handlePdfFiles(this.files);
  });
});

function handleZipFile(file) {
  $('#zipFileName').text(file.name);
  $('#zipFileSize').text(formatFileSize(file.size));
  $('#zipFileInfo').show();
}

function removeZipFile() {
  $('#zipFileInput').val('');
  $('#zipFileInfo').hide();
}

var pdfFiles = [];
var zipFile = null;

function handleZipFile(file) {
  zipFile = file; // Store the ZIP file
  console.log('ZIP file stored:', file.name, file.size);
  $('#zipFileName').text(file.name);
  $('#zipFileSize').text(formatFileSize(file.size));
  $('#zipFileInfo').show();
}

function removeZipFile() {
  zipFile = null; // Clear the ZIP file
  $('#zipFileInput').val('');
  $('#zipFileInfo').hide();
}

function handlePdfFiles(files) {
  for(var i = 0; i < files.length; i++) {
    if(files[i].name.endsWith('.pdf')) {
      pdfFiles.push(files[i]);
    }
  }
  
  renderPdfFiles();
}

function renderPdfFiles() {
  var html = '';
  pdfFiles.forEach(function(file, index) {
    html += '<div class="file-item">';
    html += '<div class="file-info">';
    html += '<div class="file-icon">📄</div>';
    html += '<div class="file-details">';
    html += '<div class="file-name">' + file.name + '</div>';
    html += '<div class="file-size">' + formatFileSize(file.size) + '</div>';
    html += '</div>';
    html += '</div>';
    html += '<button class="file-remove" onclick="removePdfFile(' + index + ')">Remove</button>';
    html += '</div>';
  });
  
  $('#pdfFileList').html(html);
}

function removePdfFile(index) {
  pdfFiles.splice(index, 1);
  renderPdfFiles();
}

function uploadFiles() {
  var uploadType = $('#uploadType').val();
  var uploadMethod = $('#uploadMethod').val();
  var folderName = $('#folderName').val();
  
  console.log('Upload started:', {uploadType, uploadMethod, folderName});
  
  // Validation
  if(uploadType === 'makeup' && !folderName) {
    showAlert('Please enter a folder name for makeup exam', 'error');
    return;
  }
  
  var formData = new FormData();
  formData.append('upload_type', uploadType);
  formData.append('folder_name', folderName);
  
  console.log('FormData being sent:', {
    upload_type: uploadType,
    folder_name: folderName,
    uploadMethod: uploadMethod
  });
  
  if(uploadMethod === 'zip') {
    console.log('ZIP file selected:', zipFile);
    if(!zipFile) {
      showAlert('Please select a ZIP file', 'error');
      return;
    }
    formData.append('zip_file', zipFile);
  } else {
    console.log('PDF files:', pdfFiles);
    if(pdfFiles.length === 0) {
      showAlert('Please select PDF files', 'error');
      return;
    }
    pdfFiles.forEach(function(file) {
      formData.append('pdf_files[]', file);
    });
  }
  
  // Debug: Log what we're sending
  console.log('FormData contents:');
  for (var pair of formData.entries()) {
    console.log(pair[0] + ': ' + pair[1]);
  }
  
  // Show progress
  $('#progressContainer').show();
  $('#uploadBtn').prop('disabled', true);
  
  // Upload
  $.ajax({
    url: 'ajax_upload_direct.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    xhr: function() {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = Math.round((evt.loaded / evt.total) * 100);
          $('#progressBarFill').css('width', percentComplete + '%').text(percentComplete + '%');
        }
      }, false);
      return xhr;
    },
    success: function(response) {
      console.log('Upload response:', response);
      $('#progressContainer').hide();
      $('#uploadBtn').prop('disabled', false);
      
      if(response.success) {
        var message = response.message;
        if (response.target_folder) {
          message += ' (Uploaded to: ' + response.target_folder + ')';
        }
        showAlert(message, 'success');
        
        // Reset form
        removeZipFile();
        pdfFiles = [];
        renderPdfFiles();
        $('#folderName').val('');
        
        // Reload folders
        loadFolders();
      } else {
        console.log('Upload failed:', response.message);
        showAlert(response.message || 'Upload failed', 'error');
      }
    },
    error: function(xhr, status, error) {
      console.log('Upload error:', {status, error, responseText: xhr.responseText});
      $('#progressContainer').hide();
      $('#uploadBtn').prop('disabled', false);
      showAlert('Upload failed: ' + error, 'error');
    }
  });
}

function loadFolders() {
  $.ajax({
    url: 'ajax_get_folders.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      if(response.success && response.folders) {
        renderFolders(response.folders);
      }
    }
  });
}

function renderFolders(folders) {
  var html = '';
  folders.forEach(function(folder) {
    html += '<div class="folder-card" onclick="viewFolder(\'' + folder.name + '\')">';
    html += '<div class="folder-icon">📁</div>';
    html += '<div class="folder-name">' + folder.name + '</div>';
    html += '<div class="folder-count">' + folder.count + ' files</div>';
    html += '</div>';
  });
  $('#folderList').html(html);
}

function viewFolder(folderName) {
  window.location.href = 'semitems.php?folder=' + encodeURIComponent(folderName);
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  var k = 1024;
  var sizes = ['Bytes', 'KB', 'MB', 'GB'];
  var i = Math.floor(Math.log(bytes) / Math.log(k));
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function showAlert(message, type) {
  var alertClass = type === 'success' ? 'alert-success-custom' : 'alert-error-custom';
  var icon = type === 'success' ? '✅' : '❌';
  
  var html = '<div class="alert-custom ' + alertClass + '">';
  html += '<strong>' + icon + ' ' + message + '</strong>';
  html += '</div>';
  
  $('#alertContainer').html(html);
  
  // Auto-hide after 5 seconds
  setTimeout(function() {
    $('#alertContainer').fadeOut(function() {
      $(this).html('').show();
    });
  }, 5000);
}
</script>

</body>
</html>


<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<style>
/* Modern Professional UI */
.files-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.folder-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    color: white;
}

.folder-header h3 {
    margin: 0 0 10px;
    font-weight: 600;
}

.folder-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.folder-stats {
    display: flex;
    gap: 30px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stat-icon {
    font-size: 24px;
}

.stat-details {
    text-align: left;
}

.stat-label {
    font-size: 12px;
    opacity: 0.9;
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
}

.file-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.file-card {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    border: 2px solid #e0e7ff;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
}

.file-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    transform: translateY(-2px);
}

.file-icon-large {
    font-size: 48px;
    text-align: center;
    margin-bottom: 15px;
}

.file-name-display {
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    word-break: break-word;
}

.file-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
    font-size: 13px;
    color: #666;
}

.file-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.file-actions {
    display: flex;
    gap: 10px;
}

.btn-action {
    flex: 1;
    padding: 10px 15px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-download {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-download:hover {
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    transform: translateY(-1px);
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-delete:hover {
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-text {
    font-size: 18px;
    margin-bottom: 10px;
    font-weight: 600;
}

.empty-subtext {
    font-size: 14px;
    color: #999;
}

.btn-back {
    background: white;
    border: 2px solid white;
    color: #667eea;
    padding: 10px 25px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: rgba(255,255,255,0.9);
    text-decoration: none;
    color: #667eea;
}

/* Delete Confirmation Modal */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-header-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    padding: 25px;
}

.modal-header-delete .close {
    color: white;
    opacity: 1;
}

.modal-body {
    padding: 30px;
}

.delete-warning {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.delete-file-name {
    font-weight: 700;
    color: #333;
    font-size: 16px;
    margin: 10px 0;
    padding: 15px;
    background: #f9fafb;
    border-radius: 8px;
}

/* Search and Filter Styles */
.search-section {
    transition: all 0.3s ease;
}

.search-section:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

#searchInput:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-controls select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* File Card Animation */
.file-card {
    transition: all 0.3s ease, opacity 0.2s ease;
}

.file-card.hidden {
    opacity: 0;
    transform: scale(0.95);
}

/* Search Results Animation */
.search-results-info {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Performance optimizations */
.file-grid {
    will-change: transform;
}

.file-card {
    will-change: transform, opacity;
}

/* Responsive search */
@media (max-width: 768px) {
    .search-section .row > div {
        margin-bottom: 15px;
    }
    
    .filter-controls .row > div {
        margin-bottom: 10px;
    }
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
        <i class="fa fa-file-pdf-o"></i> Examination Question Files
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manage_questions.php">Manage Questions</a></li>
        <li class="active">Question Files</li>
      </ol>
    </section>
    
    <!-- Main content -->
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
      
      <div class="row">
        <div class="col-xs-12">
          <div class="files-container">
                     
                  <?php
              // Get folder from URL parameter
              $folder = isset($_GET['folder']) ? $_GET['folder'] : 'DEASemester';
              
              // Sanitize folder path
              $folder = str_replace(['..', '//', '\\'], '', $folder);
              
              // Build full path
              $base_dir = __DIR__ . '/' . $folder;
              
              // Check if folder exists
              if (!is_dir($base_dir)) {
                echo "<div class='empty-state'>";
                echo "<div class='empty-icon'>❌</div>";
                echo "<div class='empty-text'>Folder Not Found</div>";
                echo "<div class='empty-subtext'>The requested folder does not exist</div>";
                echo "<a href='manage_questions.php' class='btn btn-primary' style='margin-top: 20px;'>Back to Management</a>";
                echo "</div>";
              } else {
                // Get all PDF files in the folder
                $files = [];
                $items = scandir($base_dir);
                
                foreach ($items as $item) {
                  if ($item === '.' || $item === '..') continue;
                  
                  $file_path = $base_dir . '/' . $item;
                  
                  if (is_file($file_path) && strtolower(pathinfo($item, PATHINFO_EXTENSION)) === 'pdf') {
                    $files[] = [
                      'name' => $item,
                      'path' => $file_path,
                      'size' => filesize($file_path),
                      'modified' => filemtime($file_path),
                      'course_code' => pathinfo($item, PATHINFO_FILENAME)
                    ];
                  }
                }
                
                // Sort by name
                usort($files, function($a, $b) {
                  return strcmp($a['name'], $b['name']);
                });
                
                $total_files = count($files);
                $total_size = array_sum(array_column($files, 'size'));
            ?>
            
            <!-- Folder Header -->
            <div class="folder-header">
              <h3><i class="fa fa-folder-open"></i> <?php echo htmlspecialchars(basename($folder)); ?></h3>
              <div class="folder-info">
                <div class="folder-stats">
                  <div class="stat-item">
                    <div class="stat-icon">📄</div>
                    <div class="stat-details">
                      <div class="stat-label">Total Files</div>
                      <div class="stat-value"><?php echo $total_files; ?></div>
                    </div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-icon">💾</div>
                    <div class="stat-details">
                      <div class="stat-label">Total Size</div>
                      <div class="stat-value"><?php echo formatFileSize($total_size); ?></div>
                    </div>
                  </div>
                </div>
                <a href="manage_questions.php" class="btn-back">
                  <i class="fa fa-arrow-left"></i> Back
                </a>
              </div>
            </div>
            
            <!-- Search and Filter Section -->
            <div class="search-section" style="background: #f8f9fa; border-radius: 12px; padding: 25px; margin-bottom: 25px; border: 1px solid #e9ecef;">
              <div class="row">
                <div class="col-md-8">
                  <div class="search-box">
                    <div class="input-group">
                      <span class="input-group-addon" style="background: white; border-right: none; border-radius: 8px 0 0 8px;">
                        <i class="fa fa-search" style="color: #667eea;"></i>
                      </span>
                      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search questions by course code, filename, or content..." style="border-left: none; border-radius: 0 8px 8px 0; height: 45px; font-size: 14px;">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="filter-controls">
                    <div class="row">
                      <div class="col-xs-6">
                        <select id="sortBy" class="form-control" style="height: 45px; border-radius: 8px;">
                          <option value="name">Sort by Name</option>
                          <option value="size">Sort by Size</option>
                          <option value="date">Sort by Date</option>
                        </select>
                      </div>
                      <div class="col-xs-6">
                        <select id="sortOrder" class="form-control" style="height: 45px; border-radius: 8px;">
                          <option value="asc">Ascending</option>
                          <option value="desc">Descending</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Search Results Info -->
              <div class="search-results-info" style="margin-top: 15px; display: none;">
                <div class="row">
                  <div class="col-md-6">
                    <span id="searchResultsText" style="color: #667eea; font-weight: 600; font-size: 14px;"></span>
                  </div>
                  <div class="col-md-6 text-right">
                    <button id="clearSearch" class="btn btn-sm btn-outline-primary" style="display: none;">
                      <i class="fa fa-times"></i> Clear Search
                    </button>
            </div>
                </div>
              </div>
            </div>
            
            <?php if (empty($files)): ?>
              <!-- Empty State -->
              <div class="empty-state">
                <div class="empty-icon">📭</div>
                <div class="empty-text">No PDF Files Found</div>
                <div class="empty-subtext">Upload PDF files to this folder to get started</div>
                <a href="manage_questions.php" class="btn btn-primary" style="margin-top: 20px;">
                  <i class="fa fa-upload"></i> Upload Files
                </a>
              </div>
            <?php else: ?>
              <!-- File Grid -->
              <div class="file-grid" id="fileGrid">
                <?php foreach ($files as $file): ?>
                  <div class="file-card" 
                       data-filename="<?php echo htmlspecialchars(strtolower($file['name'])); ?>"
                       data-course-code="<?php echo htmlspecialchars(strtolower($file['course_code'])); ?>"
                       data-size="<?php echo $file['size']; ?>"
                       data-date="<?php echo $file['modified']; ?>"
                       data-full-name="<?php echo htmlspecialchars(strtolower($file['name'])); ?>">
                    <div class="file-icon-large">📄</div>
                    <div class="file-name-display"><?php echo htmlspecialchars($file['course_code']); ?></div>
                    <div class="file-meta">
                      <div class="file-meta-item">
                        <span>📏</span>
                        <span><?php echo formatFileSize($file['size']); ?></span>
                      </div>
                      <div class="file-meta-item">
                        <span>🕒</span>
                        <span><?php echo date('M d, Y', $file['modified']); ?></span>
                      </div>
                    </div>
                    <div class="file-actions">
                      <button class="btn-action btn-download" onclick="downloadFile('<?php echo htmlspecialchars($folder . '/' . $file['name'], ENT_QUOTES); ?>')">
                        <i class="fa fa-download"></i> Download
                      </button>
                      <button class="btn-action btn-delete" onclick="confirmDelete('<?php echo htmlspecialchars($folder . '/' . $file['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($file['name'], ENT_QUOTES); ?>')">
                        <i class="fa fa-trash"></i> Delete
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              
              <!-- No Results Message -->
              <div id="noResults" class="empty-state" style="display: none;">
                <div class="empty-icon">🔍</div>
                <div class="empty-text">No Files Found</div>
                <div class="empty-subtext">Try adjusting your search terms or filters</div>
                <button id="clearSearchBtn" class="btn btn-primary" style="margin-top: 20px;">
                  <i class="fa fa-refresh"></i> Clear Search
                </button>
              </div>
            <?php endif; ?>
            
            <?php
              }
              
              // Helper function to format file size
              function formatFileSize($bytes) {
                if ($bytes === 0) return '0 Bytes';
                $k = 1024;
                $sizes = ['Bytes', 'KB', 'MB', 'GB'];
                $i = floor(log($bytes) / log($k));
                return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
              }
            ?>
            
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
 
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-delete">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <i class="fa fa-exclamation-triangle"></i> Confirm Deletion
        </h4>
      </div>
      <div class="modal-body">
        <p style="font-size: 16px; color: #333;">
          Are you sure you want to delete this file? This action cannot be undone.
        </p>
        <div class="delete-file-name" id="deleteFileName"></div>
        <div class="delete-warning">
          <strong>⚠️ Warning:</strong> Deleting this file will permanently remove it from the system. 
          Make sure this file is not currently being used in any scheduled examinations.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <i class="fa fa-times"></i> Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
          <i class="fa fa-trash"></i> Delete File
        </button>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/scripts.php'; ?>

<script>
var fileToDelete = null;
var allFiles = [];
var filteredFiles = [];

// Initialize search functionality
$(document).ready(function() {
  // Store all files data
  $('.file-card').each(function() {
    allFiles.push({
      element: $(this),
      filename: $(this).data('filename'),
      courseCode: $(this).data('course-code'),
      size: $(this).data('size'),
      date: $(this).data('date'),
      fullName: $(this).data('full-name')
    });
  });
  
  filteredFiles = allFiles.slice(); // Copy all files
  
  // Search functionality
  $('#searchInput').on('input', function() {
    var searchTerm = $(this).val().toLowerCase().trim();
    filterFiles(searchTerm);
  });
  
  // Sort functionality
  $('#sortBy, #sortOrder').on('change', function() {
    var searchTerm = $('#searchInput').val().toLowerCase().trim();
    filterFiles(searchTerm);
  });
  
  // Clear search functionality
  $('#clearSearch, #clearSearchBtn').on('click', function() {
    $('#searchInput').val('');
    filterFiles('');
  });
});

function filterFiles(searchTerm) {
  var sortBy = $('#sortBy').val();
  var sortOrder = $('#sortOrder').val();
  
  // Filter files based on search term
  if (searchTerm === '') {
    filteredFiles = allFiles.slice();
  } else {
    filteredFiles = allFiles.filter(function(file) {
      return file.filename.includes(searchTerm) || 
             file.courseCode.includes(searchTerm) ||
             file.fullName.includes(searchTerm);
    });
  }
  
  // Sort filtered files
  filteredFiles.sort(function(a, b) {
    var aValue, bValue;
    
    switch(sortBy) {
      case 'name':
        aValue = a.filename;
        bValue = b.filename;
        break;
      case 'size':
        aValue = a.size;
        bValue = b.size;
        break;
      case 'date':
        aValue = a.date;
        bValue = b.date;
        break;
      default:
        aValue = a.filename;
        bValue = b.filename;
    }
    
    if (sortOrder === 'desc') {
      return aValue < bValue ? 1 : -1;
    } else {
      return aValue > bValue ? 1 : -1;
    }
  });
  
  // Hide all files first
  $('.file-card').hide();
  
  // Show filtered files
  if (filteredFiles.length === 0) {
    $('#fileGrid').hide();
    $('#noResults').show();
    updateSearchResults(0, searchTerm);
  } else {
    $('#fileGrid').show();
    $('#noResults').hide();
    
    // Show filtered files in sorted order
    var $fileGrid = $('#fileGrid');
    filteredFiles.forEach(function(file) {
      $fileGrid.append(file.element);
      file.element.show();
    });
    
    updateSearchResults(filteredFiles.length, searchTerm);
  }
}

function updateSearchResults(count, searchTerm) {
  var totalFiles = allFiles.length;
  
  if (searchTerm === '') {
    $('.search-results-info').hide();
    $('#clearSearch, #clearSearchBtn').hide();
  } else {
    $('.search-results-info').show();
    $('#clearSearch, #clearSearchBtn').show();
    
    if (count === 0) {
      $('#searchResultsText').html('<i class="fa fa-search"></i> No files found for "<strong>' + searchTerm + '</strong>"');
    } else {
      $('#searchResultsText').html('<i class="fa fa-search"></i> Found <strong>' + count + '</strong> of <strong>' + totalFiles + '</strong> files for "<strong>' + searchTerm + '</strong>"');
    }
  }
}

function downloadFile(filePath) {
  window.location.href = 'secure_download.php?file=' + encodeURIComponent(filePath);
}

function confirmDelete(filePath, fileName) {
  fileToDelete = filePath;
  $('#deleteFileName').text(fileName);
  $('#deleteModal').modal('show');
}

$('#confirmDeleteBtn').click(function() {
  if (fileToDelete) {
    $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
    
    $.ajax({
      url: 'ajax_delete_file.php',
      type: 'POST',
      data: { file: fileToDelete },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#deleteModal').modal('hide');
          window.location.reload();
        } else {
          alert('Error: ' + response.message);
          $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete File');
        }
      },
      error: function() {
        alert('An error occurred while deleting the file');
        $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete File');
      }
    });
  }
});
</script>

</body>
</html>

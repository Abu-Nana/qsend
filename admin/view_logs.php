<?php
/**
 * Email Debug Log Viewer
 * View the latest email debug logs
 */

// Check if user is logged in
session_start();
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    die('Unauthorized access');
}

// Get all log files
$log_files = array_merge(
    glob(__DIR__ . '/email_debug_*.log'),
    glob(__DIR__ . '/email_postmark_*.log'),
    glob(__DIR__ . '/email_original_*.log')
);
rsort($log_files); // Sort by newest first

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Debug Logs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .log-list {
            padding: 20px;
        }
        .log-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .log-header {
            background: #e9ecef;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .log-filename {
            font-weight: bold;
            color: #495057;
        }
        .log-size {
            color: #6c757d;
            font-size: 14px;
        }
        .log-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .log-content {
            padding: 20px;
            background: #1e1e1e;
            color: #f8f8f2;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .no-logs {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .refresh-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .refresh-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Email Debug Logs</h1>
            <p>View detailed email sending logs</p>
        </div>
        
        <div class="log-list">
            <button class="refresh-btn" onclick="location.reload()">🔄 Refresh Logs</button>
            
            <?php if (empty($log_files)): ?>
                <div class="no-logs">
                    <h3>No log files found</h3>
                    <p>Run the question sending process to generate logs</p>
                </div>
            <?php else: ?>
                <?php foreach ($log_files as $log_file): ?>
                    <?php
                    $filename = basename($log_file);
                    $file_size = filesize($log_file);
                    $file_size_formatted = $file_size > 1024 ? round($file_size / 1024, 2) . ' KB' : $file_size . ' bytes';
                    $log_content = file_get_contents($log_file);
                    ?>
                    <div class="log-item">
                        <div class="log-header">
                            <div>
                                <div class="log-filename"><?php echo htmlspecialchars($filename); ?></div>
                                <div class="log-size">Size: <?php echo $file_size_formatted; ?></div>
                            </div>
                            <div class="log-actions">
                                <a href="?download=<?php echo urlencode($filename); ?>" class="btn btn-primary">📥 Download</a>
                                <a href="?delete=<?php echo urlencode($filename); ?>" class="btn btn-danger" onclick="return confirm('Delete this log file?')">🗑️ Delete</a>
                            </div>
                        </div>
                        <div class="log-content"><?php echo htmlspecialchars($log_content); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds if there are logs
        <?php if (!empty($log_files)): ?>
        setTimeout(function() {
            location.reload();
        }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>

<?php
// Handle download
if (isset($_GET['download'])) {
    $filename = $_GET['download'];
    $filepath = __DIR__ . '/' . $filename;
    
    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $filename = $_GET['delete'];
    $filepath = __DIR__ . '/' . $filename;
    
    if (file_exists($filepath)) {
        unlink($filepath);
        header('Location: view_logs.php');
        exit;
    }
}
?>

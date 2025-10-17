<?php
/**
 * GitHub Webhook Receiver for QSEND
 * 
 * This script receives webhook notifications from GitHub
 * and triggers the deployment process.
 * 
 * Place this file on your EC2 instance in a web-accessible directory
 * Example: /var/www/webhook/qsend-webhook.php
 * 
 * Then configure GitHub webhook to point to:
 * http://your-server-ip/webhook/qsend-webhook.php
 */

// Configuration
define('WEBHOOK_SECRET', 'your-secure-webhook-secret-here'); // Change this!
define('DEPLOY_SCRIPT', '/home/ubuntu/docker-apps/questionsending/deploy.sh');
define('LOG_FILE', '/home/ubuntu/docker-apps/questionsending/webhook.log');
define('ALLOWED_BRANCH', 'main'); // or 'master' depending on your setup

// Logging function
function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$message}\n";
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);
    echo $logEntry;
}

// Verify GitHub signature
function verifySignature($payload, $signature) {
    if (empty($signature)) {
        return false;
    }
    
    $hash = 'sha256=' . hash_hmac('sha256', $payload, WEBHOOK_SECRET);
    return hash_equals($hash, $signature);
}

// Start processing
logMessage("=== Webhook request received ===");

// Get request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    logMessage("ERROR: Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    exit('Method Not Allowed');
}

// Get headers
$headers = getallheaders();
$signature = $headers['X-Hub-Signature-256'] ?? '';
$event = $headers['X-GitHub-Event'] ?? '';

logMessage("GitHub Event: " . $event);

// Get payload
$payload = file_get_contents('php://input');

// Verify signature
if (!verifySignature($payload, $signature)) {
    http_response_code(401);
    logMessage("ERROR: Invalid signature");
    exit('Unauthorized');
}

logMessage("Signature verified successfully");

// Parse JSON payload
$data = json_decode($payload, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    logMessage("ERROR: Invalid JSON payload");
    exit('Bad Request');
}

// Process different event types
switch ($event) {
    case 'ping':
        logMessage("Ping event received - webhook is configured correctly");
        http_response_code(200);
        echo json_encode(['status' => 'ok', 'message' => 'Webhook is configured correctly']);
        exit;
        
    case 'push':
        // Extract branch name
        $ref = $data['ref'] ?? '';
        $branch = str_replace('refs/heads/', '', $ref);
        
        logMessage("Push event detected for branch: " . $branch);
        
        // Check if it's the correct branch
        if ($branch !== ALLOWED_BRANCH) {
            logMessage("Ignoring push to branch: " . $branch);
            http_response_code(200);
            echo json_encode(['status' => 'ignored', 'message' => 'Not the deployment branch']);
            exit;
        }
        
        // Log commit information
        $commits = $data['commits'] ?? [];
        $committer = $data['pusher']['name'] ?? 'Unknown';
        logMessage("Commits by {$committer}: " . count($commits));
        
        foreach ($commits as $commit) {
            $message = $commit['message'] ?? 'No message';
            logMessage("  - " . substr($message, 0, 50));
        }
        
        // Execute deployment script
        logMessage("Starting deployment process...");
        
        // Check if deployment script exists
        if (!file_exists(DEPLOY_SCRIPT)) {
            logMessage("ERROR: Deployment script not found: " . DEPLOY_SCRIPT);
            http_response_code(500);
            exit('Deployment script not found');
        }
        
        // Make script executable
        chmod(DEPLOY_SCRIPT, 0755);
        
        // Execute in background to not timeout
        $command = 'nohup ' . DEPLOY_SCRIPT . ' > /tmp/deploy_output.log 2>&1 &';
        exec($command, $output, $return_var);
        
        if ($return_var === 0) {
            logMessage("Deployment script executed successfully");
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Deployment started',
                'branch' => $branch,
                'commits' => count($commits)
            ]);
        } else {
            logMessage("ERROR: Failed to execute deployment script");
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to start deployment'
            ]);
        }
        break;
        
    case 'pull_request':
        $action = $data['action'] ?? '';
        $prNumber = $data['number'] ?? 'unknown';
        $prTitle = $data['pull_request']['title'] ?? 'No title';
        
        logMessage("Pull request #{$prNumber} - Action: {$action}");
        logMessage("Title: " . $prTitle);
        
        // You can add PR-specific actions here if needed
        // For now, we'll just log and acknowledge
        
        http_response_code(200);
        echo json_encode([
            'status' => 'received',
            'message' => 'Pull request event logged'
        ]);
        break;
        
    default:
        logMessage("Unhandled event type: " . $event);
        http_response_code(200);
        echo json_encode([
            'status' => 'ignored',
            'message' => 'Event type not configured for deployment'
        ]);
        break;
}

logMessage("=== Webhook processing completed ===\n");
?>


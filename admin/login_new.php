<?php
/**
 * Secure Login Handler
 * 
 * This file handles admin authentication with:
 * - SQL injection protection
 * - Prepared statements
 * - Bcrypt password hashing
 * - Rate limiting
 * - Session security
 * 
 * @package QSEND
 * @version 2.0
 */

// Include security class
require_once __DIR__ . '/../config/Security.php';

// Start secure session
Security::startSecureSession();

// Redirect if already logged in
if (isset($_SESSION['admin'])) {
    header('location: home.php');
    exit;
}

if (isset($_POST['login'])) {
    // Sanitize inputs
    $username = Security::sanitizeInput($_POST['username']);
    $password = $_POST['password']; // Don't sanitize password - verify as-is
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please provide both username and password';
        header('location: index.php');
        exit;
    }
    
    // Initialize security class
    $security = new Security();
    
    // Authenticate user
    $result = $security->authenticateAdmin($username, $password);
    
    if ($result['success']) {
        // Set session with regenerated ID for security
        session_regenerate_id(true);
        $_SESSION['admin'] = $result['user']['id'];
        $_SESSION['username'] = $result['user']['username'];
        $_SESSION['last_activity'] = time();
        
        // Log successful login
        error_log("Successful login: {$username} from {$_SERVER['REMOTE_ADDR']}");
        
        header('location: home.php');
        exit;
    } else {
        $_SESSION['error'] = $result['error'];
        
        // Log failed login
        error_log("Failed login attempt: {$username} from {$_SERVER['REMOTE_ADDR']}");
        
        header('location: index.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid request method';
    header('location: index.php');
    exit;
}


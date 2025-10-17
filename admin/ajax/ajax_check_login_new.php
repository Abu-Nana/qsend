<?php
/**
 * Secure AJAX Login Handler
 * 
 * This file handles user authentication via AJAX with:
 * - SQL injection protection
 * - Prepared statements
 * - Bcrypt password hashing (with MD5 legacy support)
 * - Rate limiting
 * - Session security
 * 
 * @package QSEND
 * @version 2.0
 */

// Include security class
require_once __DIR__ . '/../../config/Security.php';

// Start secure session
Security::startSecureSession();

// Initialize response
$response = ['success' => false, 'data' => '0'];

// Check if POST data exists
if (!isset($_POST['id_usr1']) || !isset($_POST['id_pwd1'])) {
    echo "0";
    exit;
}

// Sanitize inputs
$username = Security::sanitizeInput($_POST['id_usr1']);
$password = $_POST['id_pwd1']; // Don't sanitize password

// Validate inputs
if (empty($username) || empty($password)) {
    echo "0";
    exit;
}

// Initialize security class
$security = new Security();

// Authenticate user
$result = $security->authenticateUser($username, $password);

if ($result['success']) {
    $user = $result['user'];
    
    // Set session variables
    session_regenerate_id(true);
    $_SESSION['email'] = $username;
    $_SESSION['usrid'] = $user['usr_name'];
    $_SESSION['name'] = $user['acct_name'];
    $_SESSION['u_cat'] = $user['usr_cat'];
    $_SESSION['pwd_reset'] = $user['pwd_reset'];
    $_SESSION['last_activity'] = time();
    
    // Determine user role and redirect code
    $data = "0";
    switch ($user['usr_cat']) {
        case 'ADMIN':
            $_SESSION['role'] = "System Administrator";
            $data = "1";
            break;
        case 'DEAN':
        case 'FAEO':
            $_SESSION['role'] = "Faculty User";
            $_SESSION['fac_id'] = $user['unit_code'];
            $data = "2";
            break;
        case 'FHOD':
        case 'FDEO':
            $_SESSION['role'] = "Department User";
            $_SESSION['dept_id'] = $user['unit_code'];
            $data = "3";
            break;
        case 'TECHO':
        case 'HDSS':
            $_SESSION['role'] = "Helpdesk/Support";
            $data = "4";
            break;
        case 'FPCT':
            $_SESSION['role'] = "Program User";
            $_SESSION['prog_id'] = $user['unit_code'];
            $data = "5";
            break;
        case 'SPGS':
            $data = "6";
            break;
        case 'MGMT':
            $_SESSION['role'] = "Management User";
            $data = "7";
            break;
    }
    
    // Check if password reset required
    if ($user['pwd_reset'] == 1) {
        $data = "9";
    }
    
    // Log successful login
    error_log("Successful login: {$username} from {$_SERVER['REMOTE_ADDR']}");
    
    echo $data;
} else {
    // Log failed login
    error_log("Failed login attempt: {$username} from {$_SERVER['REMOTE_ADDR']}");
    
    echo "0";
}



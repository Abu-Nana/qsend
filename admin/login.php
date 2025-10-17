<?php
/**
 * Simple Login Handler for QSEND
 * Uses existing admin table with bcrypt passwords
 */

session_start();

// Include database connection
require_once __DIR__ . '/../config/database.php';

// Redirect if already logged in
if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
    header('Location: home.php');
    exit;
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    
    // Get form inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please provide both username and password';
        header('Location: ../index.php');
        exit;
    }
    
    try {
        // Get database connection
        $pdo = Database::getPDO();
        
        // Query user from admin table
        $stmt = $pdo->prepare("SELECT id, username, password, firstname, lastname, cat FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() === 0) {
            // User not found
            $_SESSION['error'] = 'Invalid username or password';
            header('Location: ../index.php');
            exit;
        }
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password (supports bcrypt)
        if (password_verify($password, $user['password'])) {
            // Password correct - set session and redirect
            session_regenerate_id(true);
            
            $_SESSION['admin'] = $user['id'];
            $_SESSION['usrid'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['firstname'] . ' ' . $user['lastname'];
            $_SESSION['u_cat'] = $user['cat'];
            $_SESSION['email'] = $user['username'];
            
            // Set role based on category
            if (strtolower($user['cat']) === 'dea') {
                $_SESSION['role'] = 'System Administrator';
            } else {
                $_SESSION['role'] = 'Faculty User';
            }
            
            // Redirect to home page
            header('Location: home.php');
            exit;
            
        } else {
            // Password incorrect
            $_SESSION['error'] = 'Invalid username or password';
            header('Location: ../index.php');
            exit;
        }
        
    } catch (PDOException $e) {
        // Database error
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error'] = 'System error. Please try again later.';
        header('Location: ../index.php');
        exit;
    }
    
} else {
    // Invalid request
    $_SESSION['error'] = 'Invalid request';
    header('Location: ../index.php');
    exit;
}
?>

<?php
/**
 * Security Class
 * 
 * Handles authentication, password management, and security features
 * 
 * @package QSEND
 * @version 2.0
 */

class Security {
    private $conn;
    private $pdo;
    
    public function __construct() {
        require_once __DIR__ . '/database.php';
        $this->conn = Database::getMySQLi();
        $this->pdo = Database::getPDO();
    }
    
    /**
     * Hash password using bcrypt
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Verify password against hash
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        // Check if hash is MD5 (legacy) - 32 characters
        if (strlen($hash) === 32 && ctype_xdigit($hash)) {
            // Legacy MD5 verification
            return md5($password) === $hash;
        }
        
        // Modern bcrypt verification
        return password_verify($password, $hash);
    }
    
    /**
     * Check if password needs rehashing (legacy MD5 upgrade)
     * @param string $hash Current password hash
     * @return bool
     */
    public static function needsRehash($hash) {
        // If it's MD5 (32 chars), needs upgrade
        if (strlen($hash) === 32 && ctype_xdigit($hash)) {
            return true;
        }
        
        // Check if bcrypt needs upgrade
        return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Authenticate user with SQL injection protection
     * @param string $username Username
     * @param string $password Plain text password
     * @return array|false User data or false on failure
     */
    public function authenticateAdmin($username, $password) {
        // Check for login attempts (rate limiting)
        if ($this->isAccountLocked($username)) {
            return [
                'success' => false,
                'error' => 'Account temporarily locked due to multiple failed attempts. Please try again later.'
            ];
        }
        
        // Prepared statement to prevent SQL injection
        $stmt = $this->conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $this->logFailedAttempt($username);
            return [
                'success' => false,
                'error' => 'Invalid username or password'
            ];
        }
        
        $user = $result->fetch_assoc();
        $stmt->close();
        
        // Verify password
        if (!self::verifyPassword($password, $user['password'])) {
            $this->logFailedAttempt($username);
            return [
                'success' => false,
                'error' => 'Invalid username or password'
            ];
        }
        
        // Password verified successfully
        $this->clearFailedAttempts($username);
        
        // Upgrade legacy MD5 password to bcrypt
        if (self::needsRehash($user['password'])) {
            $this->upgradePassword($user['id'], $password);
        }
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
    
    /**
     * Authenticate user (PDO version for usr_acct table)
     * @param string $username Username
     * @param string $password Plain text password
     * @return array|false User data or false on failure
     */
    public function authenticateUser($username, $password) {
        // Check for login attempts
        if ($this->isAccountLocked($username)) {
            return [
                'success' => false,
                'error' => 'Account temporarily locked due to multiple failed attempts. Please try again later.'
            ];
        }
        
        // Prepared statement to prevent SQL injection
        $stmt = $this->pdo->prepare("SELECT usr_name, acct_name, usr_cat, usr_passwords, unit_code, pwd_reset 
                                     FROM usr_acct 
                                     WHERE usr_name = :username 
                                     LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            $this->logFailedAttempt($username);
            return [
                'success' => false,
                'error' => 'Invalid username or password'
            ];
        }
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password
        if (!self::verifyPassword($password, $user['usr_passwords'])) {
            $this->logFailedAttempt($username);
            return [
                'success' => false,
                'error' => 'Invalid username or password'
            ];
        }
        
        // Password verified successfully
        $this->clearFailedAttempts($username);
        
        // Upgrade legacy MD5 password to bcrypt
        if (self::needsRehash($user['usr_passwords'])) {
            $this->upgradeUserPassword($username, $password);
        }
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
    
    /**
     * Check if account is locked due to failed attempts
     * @param string $username
     * @return bool
     */
    private function isAccountLocked($username) {
        $lockoutTime = defined('LOGIN_LOCKOUT_TIME') ? LOGIN_LOCKOUT_TIME : 900;
        $maxAttempts = defined('MAX_LOGIN_ATTEMPTS') ? MAX_LOGIN_ATTEMPTS : 5;
        
        $stmt = $this->conn->prepare("SELECT COUNT(*) as attempts 
                                      FROM login_attempts 
                                      WHERE username = ? 
                                      AND attempt_time > DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->bind_param("si", $username, $lockoutTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['attempts'] >= $maxAttempts;
    }
    
    /**
     * Log failed login attempt
     * @param string $username
     */
    private function logFailedAttempt($username) {
        // Create login_attempts table if it doesn't exist
        $this->createLoginAttemptsTable();
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt = $this->conn->prepare("INSERT INTO login_attempts (username, ip_address, attempt_time) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $username, $ip);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Clear failed login attempts
     * @param string $username
     */
    private function clearFailedAttempts($username) {
        $stmt = $this->conn->prepare("DELETE FROM login_attempts WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Upgrade password from MD5 to bcrypt
     * @param int $userId
     * @param string $plainPassword
     */
    private function upgradePassword($userId, $plainPassword) {
        $newHash = self::hashPassword($plainPassword);
        $stmt = $this->conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newHash, $userId);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Upgrade user password from MD5 to bcrypt
     * @param string $username
     * @param string $plainPassword
     */
    private function upgradeUserPassword($username, $plainPassword) {
        $newHash = self::hashPassword($plainPassword);
        $stmt = $this->pdo->prepare("UPDATE usr_acct SET usr_passwords = :password WHERE usr_name = :username");
        $stmt->bindParam(':password', $newHash, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    /**
     * Create login_attempts table if it doesn't exist
     */
    private function createLoginAttemptsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS login_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            attempt_time DATETIME NOT NULL,
            INDEX idx_username_time (username, attempt_time)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->conn->query($sql);
    }
    
    /**
     * Sanitize input (additional layer of protection)
     * @param string $input
     * @return string
     */
    public static function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }
    
    /**
     * Generate CSRF token
     * @return string
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     * @param string $token
     * @return bool
     */
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Start secure session
     */
    public static function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Configure session security
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 1 : 0);
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
            
            // Regenerate session ID on first load
            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
            }
        }
    }
}



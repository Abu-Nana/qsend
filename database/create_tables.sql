-- Create user accounts table
CREATE TABLE IF NOT EXISTS `usr_acct` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_name` varchar(100) NOT NULL,
  `acct_name` varchar(255) NOT NULL,
  `usr_passwords` varchar(255) NOT NULL,
  `usr_cat` varchar(50) NOT NULL,
  `unit_code` varchar(50) DEFAULT NULL,
  `pwd_reset` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `usr_name` (`usr_name`),
  KEY `usr_cat` (`usr_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create login attempts table (already exists in database/login_attempts.sql but let's ensure it's created)
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` datetime NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `ip_address` (`ip_address`),
  KEY `attempt_time` (`attempt_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert a default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO `usr_acct` (`usr_name`, `acct_name`, `usr_passwords`, `usr_cat`, `pwd_reset`) 
VALUES ('admin', 'System Administrator', MD5('admin123'), 'ADMIN', 0)
ON DUPLICATE KEY UPDATE `usr_name`='admin';


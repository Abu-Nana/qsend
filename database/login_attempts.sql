-- Login Attempts Tracking Table
-- This table is used for rate limiting and brute force protection

CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL COMMENT 'Supports both IPv4 and IPv6',
    `attempt_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_username_time` (`username`, `attempt_time`),
    INDEX `idx_ip_time` (`ip_address`, `attempt_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Add cleanup for old attempts (older than 30 days)
-- You can run this as a scheduled job

-- DELETE FROM login_attempts WHERE attempt_time < DATE_SUB(NOW(), INTERVAL 30 DAY);



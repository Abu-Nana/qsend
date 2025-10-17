<?php
/**
 * Reset testadmin password to dea123456
 */

require_once __DIR__ . '/../conn.php';

$username = 'testadmin';
$new_password = 'dea123456';
$password_hash = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);

echo "Resetting password for: $username\n";
echo "New password: $new_password\n";
echo "New hash: $password_hash\n\n";

$stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $password_hash, $username);

if ($stmt->execute()) {
    echo "✅ Password reset successfully!\n";
    echo "You can now login with:\n";
    echo "  Username: testadmin\n";
    echo "  Password: dea123456\n";
} else {
    echo "❌ Failed to reset password: " . $stmt->error . "\n";
}

$stmt->close();
?>


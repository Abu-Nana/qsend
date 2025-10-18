<?php
session_start();
require_once __DIR__ . '/../config/database.php';
$conn = Database::getPDO();

$t_usr = $_POST['id_usr1'] ?? 'testuser';
$t_pwd = $_POST['id_pwd1'] ?? 'testpass';
$pwd = md5($t_pwd);

echo "=== Login Debug Info ===\n\n";
echo "Username: $t_usr\n";
echo "Password (plain): $t_pwd\n";
echo "Password (MD5): $pwd\n\n";

// Check if user exists
$query1 = "SELECT id, username, password, cat FROM admin WHERE username = '$t_usr'";
echo "Query 1 (Check user exists):\n$query1\n\n";

$stmt1 = $conn->prepare($query1);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

echo "User found: " . count($result1) . " record(s)\n";
if (count($result1) > 0) {
    echo "User details:\n";
    print_r($result1[0]);
    echo "\n";
    
    $stored_pwd = $result1[0]['password'];
    echo "Stored password hash: $stored_pwd\n";
    echo "Provided password hash: $pwd\n";
    echo "Passwords match: " . ($stored_pwd === $pwd ? "YES" : "NO") . "\n\n";
}

// Now try the full query
$query2 = "SELECT id, username, firstname, lastname, cat, password
FROM admin
WHERE username ='$t_usr' AND password='$pwd'";

echo "Query 2 (Full login query):\n$query2\n\n";

$stmt2 = $conn->prepare($query2);
$stmt2->execute();
echo "Rows returned: " . $stmt2->rowCount() . "\n";

$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
if (count($result2) > 0) {
    echo "\nLogin would succeed!\n";
    print_r($result2[0]);
} else {
    echo "\nLogin would fail - no matching record\n";
}
?>


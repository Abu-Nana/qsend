<?php
/**
 * Truncate Registration Data
 * 
 * @package QSEND
 * @version 2.0
 */

include 'includes/session.php';

try {
    // Delete all records
    $result = $conn->query("DELETE FROM student_registrations");
    
    if($result) {
        $_SESSION['success'] = 'All registration data has been deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete registration data.';
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
}

header('location: reg-data.php');
exit;
?>


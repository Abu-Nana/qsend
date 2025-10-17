<?php
/**
 * Delete Single Registration Record
 * 
 * @package QSEND
 * @version 2.0
 */

include 'includes/session.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        $stmt = $conn->prepare("DELETE FROM student_registrations WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            $_SESSION['success'] = 'Record deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete record.';
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'Invalid request.';
}

header('location: reg-data.php');
exit;
?>


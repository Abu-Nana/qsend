<?php
	// Start secure session
	require_once __DIR__ . '/../../config/Security.php';
	Security::startSecureSession();
	
	// Include database connection
	include 'includes/conn.php';

	// Check if user is logged in
	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		header('location: index.php');
		exit;
	}

	// Get user details using prepared statement (secure)
	$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
	$stmt->bind_param("s", $_SESSION['admin']);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	$stmt->close();
	
	// If user not found, redirect to login
	if (!$user) {
		unset($_SESSION['admin']);
		header('location: index.php');
		exit;
	}
?>
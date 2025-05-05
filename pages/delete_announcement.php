<?php
include '../config.php';
include '../includes/db.php';
session_start();

// Ensure only admin can perform this action
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $announcement_id = $_GET['id'];

    // Delete announcement from the database
    $deleteQuery = "DELETE FROM announcements WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $announcement_id);
    $stmt->execute();
}

header("Location: " . BASE_URL . "pages/manage_announcements.php");
exit();
?>

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
    $user_id = $_GET['id'];

    // Prevent deletion of other admins
    $checkQuery = "SELECT role FROM users WHERE id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['role'] == 'admin') {
        die("Error: Cannot delete an admin account.");
    }

    // Delete user
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        header("Location: " . BASE_URL . "pages/manage_users.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
}
?>

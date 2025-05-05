<?php
include '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];

// Delete from database
$stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_contacts.php?success=Message deleted successfully");
} else {
    die("Error deleting message.");
}
?>

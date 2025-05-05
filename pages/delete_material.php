<?php
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'teacher' && $_SESSION['role'] != 'admin')) {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $teacher_id = $_SESSION['user_id'];

    $deleteQuery = "DELETE FROM learning_materials WHERE id = ? AND uploaded_by = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $id, $teacher_id);

    if ($stmt->execute()) {
        logActivity($conn, $teacher_id, "Deleted Learning Material (ID: $id)");
        header("Location: manage_materials.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error deleting material.</div>";
    }
}
?>

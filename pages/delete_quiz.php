<?php
include '../config.php';
include '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    die("<div class='alert alert-danger text-center'>Unauthorized access!</div>");
}

if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];

    // Ensure the quiz belongs to the logged-in teacher
    $deleteQuery = "DELETE FROM quizzes WHERE id = $quiz_id AND created_by = " . $_SESSION['user_id'];
    
    if ($conn->query($deleteQuery) === TRUE) {
        header("Location: " . BASE_URL . "pages/dashboard.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error deleting quiz: " . $conn->error . "</div>";
    }
}
?>

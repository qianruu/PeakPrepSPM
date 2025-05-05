<?php
include '../config.php';
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}
?>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>
    
    <div class="d-flex justify-content-center mt-4">
        <a href="manage_users.php" class="btn btn-danger me-3">Manage Users</a>
        <a href="manage_quizzes.php" class="btn btn-primary me-3">Manage Quizzes</a>
        <a href="manage_materials.php" class="btn btn-success me-3">Manage Materials</a>
        <a href="manage_activity.php" class="btn btn-info me-3">User Activity</a>
        <a href="manage_performance.php" class="btn btn-warning me-3">User Performance</a>
        <a href="manage_announcements.php" class="btn btn-secondary">Announcements</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

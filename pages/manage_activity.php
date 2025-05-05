<?php
include '../config.php';
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

// Fetch all activity logs
$sql = "SELECT activity_log.id, users.username, users.role, activity_log.action, activity_log.timestamp 
        FROM activity_log 
        JOIN users ON activity_log.user_id = users.id 
        ORDER BY activity_log.timestamp DESC";
$logs = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center">User Activity Logs</h2>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($log = $logs->fetch_assoc()): ?>
            <tr>
                <td><?php echo $log['id']; ?></td>
                <td><?php echo htmlspecialchars($log['username']); ?></td>
                <td><?php echo ucfirst($log['role']); ?></td>
                <td><?php echo htmlspecialchars($log['action']); ?></td>
                <td><?php echo $log['timestamp']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

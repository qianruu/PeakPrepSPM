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

// Handle new announcement submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO announcements (title, message, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $message);
    
    if ($stmt->execute()) {
        header("Location: " . BASE_URL . "pages/manage_announcements.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error posting announcement.</div>";
    }
}

// Fetch all announcements
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$announcements = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center">Manage Announcements</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post Announcement</button>
    </form>

    <h3 class="mt-5">Recent Announcements</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Posted On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $announcements->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="delete_announcement.php?id=<?php echo $row['id']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this announcement?');">
                        Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

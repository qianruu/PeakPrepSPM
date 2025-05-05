<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/header.php';
include '../includes/db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch messages from database
$query = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<div class="container mt-5">
    <h2 class="text-center">Manage Contact Messages</h2>
    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Received At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($message = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $message['id']; ?></td>
                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                    <td><?php echo htmlspecialchars($message['subject']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
                    <td><?php echo $message['created_at']; ?></td>
                    <td>
                        <a href="delete_contact.php?id=<?php echo $message['id']; ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

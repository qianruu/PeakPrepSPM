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

// Fetch all quizzes
$sql = "SELECT quizzes.id, quizzes.subject, quizzes.level, users.username AS creator 
        FROM quizzes 
        JOIN users ON quizzes.created_by = users.id 
        ORDER BY quizzes.subject ASC";
$quizzes = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center">Manage Quizzes</h2>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Level</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($quiz = $quizzes->fetch_assoc()): ?>
            <tr>
                <td><?php echo $quiz['id']; ?></td>
                <td><?php echo $quiz['subject']; ?></td>
                <td><?php echo ucfirst($quiz['level']); ?></td>
                <td><?php echo $quiz['creator']; ?></td>
                <td>
                    <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this quiz?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

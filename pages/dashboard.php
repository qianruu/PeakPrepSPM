<?php
include '../includes/header.php';
include '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$quizQuery = "SELECT * FROM quizzes WHERE created_by = $teacher_id";
$quizResult = $conn->query($quizQuery);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p class="text-center">Manage quizzes, track students' progress, and provide feedback.</p>
    <div class="text-center mb-4">
        <a href="<?php echo BASE_URL; ?>pages/create_quiz.php" class="btn btn-primary rounded-pill px-4 py-2">Create a Quiz</a>
        <a href="<?php echo BASE_URL; ?>pages/view_student_results.php" class="btn btn-info rounded-pill px-4 py-2">View Student Results</a>
    </div>

    <!-- Card for Listing Created Quizzes -->
    <div class="card shadow p-4">
        <h3 class="text-center mb-3">Your Created Quizzes</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($quizResult->num_rows > 0): ?>
                    <?php while ($row = $quizResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo ucfirst($row['level']); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>pages/edit_quiz.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="<?php echo BASE_URL; ?>pages/delete_quiz.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No quizzes created yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

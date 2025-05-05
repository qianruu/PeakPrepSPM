<?php
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only teachers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_student_results.php");
    exit();
}

$result_id = $_GET['id'];
$teacher_id = $_SESSION['user_id'];

// Fetch result details
$query = "SELECT results.id, results.user_id, users.username, results.score, results.feedback, quizzes.subject, quizzes.level, classes.class_name 
          FROM results 
          JOIN users ON results.user_id = users.id 
          JOIN quizzes ON results.quiz_id = quizzes.id 
          JOIN classes ON quizzes.class_id = classes.id 
          WHERE results.id = ? AND quizzes.teacher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $result_id, $teacher_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo "<div class='alert alert-danger'>Result not found or unauthorized access.</div>";
    exit();
}

// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    $updateQuery = "UPDATE results SET feedback=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $feedback, $result_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Feedback provided successfully!</div>";
        header("Refresh: 2; URL=view_student_results.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error saving feedback.</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Provide Feedback for <?php echo htmlspecialchars($result['username']); ?></h2>
    <p><strong>Class:</strong> <?php echo htmlspecialchars($result['class_name']); ?></p>
    <p><strong>Subject:</strong> <?php echo htmlspecialchars($result['subject']); ?></p>
    <p><strong>Level:</strong> <?php echo htmlspecialchars($result['level']); ?></p>
    <p><strong>Score:</strong> <?php echo $result['score']; ?></p>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Feedback</label>
            <textarea name="feedback" class="form-control" required><?php  echo isset($result['feedback']) ? htmlspecialchars($result['feedback']) : "No feedback yet"; ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Submit Feedback</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

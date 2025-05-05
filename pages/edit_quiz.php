<?php
include '../config.php';
include '../includes/header.php';
include '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    die("<div class='alert alert-danger text-center'>Unauthorized access! Please log in as a teacher.</div>");
}

if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];
    $quizQuery = "SELECT * FROM quizzes WHERE id = $quiz_id AND created_by = " . $_SESSION['user_id'];
    $quizResult = $conn->query($quizQuery);
    
    if ($quizResult->num_rows == 0) {
        die("<div class='alert alert-danger text-center'>Quiz not found or unauthorized access.</div>");
    }
    $quiz = $quizResult->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $option_a = mysqli_real_escape_string($conn, $_POST['option_a']);
    $option_b = mysqli_real_escape_string($conn, $_POST['option_b']);
    $option_c = mysqli_real_escape_string($conn, $_POST['option_c']);
    $option_d = mysqli_real_escape_string($conn, $_POST['option_d']);
    $correct_option = mysqli_real_escape_string($conn, $_POST['correct_option']);

    $updateQuery = "UPDATE quizzes SET subject='$subject', level='$level', question='$question', option_a='$option_a', option_b='$option_b', option_c='$option_c', option_d='$option_d', correct_option='$correct_option' WHERE id=$quiz_id AND created_by=" . $_SESSION['user_id'];
    
    if ($conn->query($updateQuery) === TRUE) {
        header("Location: " . BASE_URL . "pages/dashboard.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error updating quiz: " . $conn->error . "</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Edit Quiz</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" value="<?php echo $quiz['subject']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Level (Form 4 / Form 5)</label>
            <select name="level" id="level" class="form-control rounded-pill" required>
                <option value="form4" <?php echo ($quiz['level'] == 'form4') ? 'selected' : ''; ?>>Level 4</option>
                <option value="form5" <?php echo ($quiz['level'] == 'form5') ? 'selected' : ''; ?>>Level 5</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Question</label>
            <textarea name="question" class="form-control" required><?php echo $quiz['question']; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Option A</label>
            <input type="text" name="option_a" class="form-control" value="<?php echo $quiz['option_a']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Option B</label>
            <input type="text" name="option_b" class="form-control" value="<?php echo $quiz['option_b']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Option C</label>
            <input type="text" name="option_c" class="form-control" value="<?php echo $quiz['option_c']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Option D</label>
            <input type="text" name="option_d" class="form-control" value="<?php echo $quiz['option_d']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correct Answer (A / B / C / D)</label>
            <input type="text" name="correct_option" class="form-control" value="<?php echo $quiz['correct_option']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Quiz</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

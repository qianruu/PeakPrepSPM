<?php
include '../config.php'; // Ensure BASE_URL is defined
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only teachers can create quizzes
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    die("<div class='alert alert-danger text-center'>Unauthorized access! Please log in as a teacher.</div>");
}

$teacher_id = $_SESSION['user_id'];

// Fetch available classes assigned to this teacher
$classQuery = "SELECT id, class_name FROM classes WHERE teacher_id = ?";
$stmt = $conn->prepare($classQuery);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$classes = $stmt->get_result();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (!isset($_POST['subject'], $_POST['level'], $_POST['class_id'], $_POST['question'], $_POST['option_a'], $_POST['option_b'], $_POST['option_c'], $_POST['option_d'], $_POST['correct_option'], $_POST['explanation'])) {
        die("<div class='alert alert-danger text-center'>Error: Missing form fields!</div>");
    }

    // Sanitize user input
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $option_a = mysqli_real_escape_string($conn, $_POST['option_a']);
    $option_b = mysqli_real_escape_string($conn, $_POST['option_b']);
    $option_c = mysqli_real_escape_string($conn, $_POST['option_c']);
    $option_d = mysqli_real_escape_string($conn, $_POST['option_d']);
    $correct_option = mysqli_real_escape_string($conn, $_POST['correct_option']);
    $explanation = mysqli_real_escape_string($conn, $_POST['explanation']); // New field for explanation

    // Ensure all fields are filled
    if (empty($subject) || empty($level) || empty($class_id) || empty($question) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($correct_option) || empty($explanation)) {
        die("<div class='alert alert-danger text-center'>Error: All fields are required.</div>");
    }

    // Insert into quizzes table
    $sql = "INSERT INTO quizzes (subject, level, class_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, teacher_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssssi", $subject, $level, $class_id, $question, $option_a, $option_b, $option_c, $option_d, $correct_option, $explanation, $teacher_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Quiz created successfully! Redirecting...</div>";
        header("Refresh: 2; URL=" . BASE_URL . "pages/dashboard_teacher.php");
        exit();
    } else {
        die("<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>");
    }
}
?>

<!-- Quiz Creation Form -->
<div class="container mt-5">
    <h2 class="text-center">Create a New Quiz</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control rounded-pill" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Level (Form 4 / Form 5)</label>
            <select name="level" class="form-control rounded-pill" required>
                <option value="form4">Form 4</option>
                <option value="form5">Form 5</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign to Class</label>
            <select name="class_id" class="form-control rounded-pill" required>
                <option value="">-- Select Class --</option>
                <?php while ($class = $classes->fetch_assoc()): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Question</label>
            <textarea name="question" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Option A</label>
            <input type="text" name="option_a" class="form-control rounded-pill" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Option B</label>
            <input type="text" name="option_b" class="form-control rounded-pill" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Option C</label>
            <input type="text" name="option_c" class="form-control rounded-pill" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Option D</label>
            <input type="text" name="option_d" class="form-control rounded-pill" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correct Answer (A / B / C / D)</label>
            <input type="text" name="correct_option" class="form-control rounded-pill" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Explanation for Correct Answer</label>
            <textarea name="explanation" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">Create Quiz</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

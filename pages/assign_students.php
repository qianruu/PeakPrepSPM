<?php
include '../config.php';
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

$teacher_id = $_SESSION['user_id'];

// Fetch teacher's classes
$classesQuery = "SELECT * FROM classes WHERE teacher_id = ?";
$stmt = $conn->prepare($classesQuery);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$classes = $stmt->get_result();

// Fetch students
$studentsQuery = "SELECT id, username FROM users WHERE role = 'student'";
$students = $conn->query($studentsQuery);

// Assign student to class
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $student_id = $_POST['student_id'];

    $assignQuery = "INSERT INTO class_students (class_id, student_id) VALUES (?, ?)";
    $stmt = $conn->prepare($assignQuery);
    $stmt->bind_param("ii", $class_id, $student_id);
    $stmt->execute();

    header("Location: assign_students.php");
    exit();
}
?>

<div class="container mt-5">
    <h2>Assign Students to Classes</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Select Class</label>
            <select name="class_id" class="form-control" required>
                <?php while ($class = $classes->fetch_assoc()): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Select Student</label>
            <select name="student_id" class="form-control" required>
                <?php while ($student = $students->fetch_assoc()): ?>
                    <option value="<?php echo $student['id']; ?>"><?php echo $student['username']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Assign Student</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

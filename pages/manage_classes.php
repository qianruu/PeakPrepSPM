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

// Handle class creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_name'])) {
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);

    $sql = "INSERT INTO classes (class_name, teacher_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $class_name, $teacher_id);
    
    if ($stmt->execute()) {
        header("Location: " . BASE_URL . "pages/manage_classes.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error creating class.</div>";
    }
}

// Fetch classes created by this teacher
$sql = "SELECT * FROM classes WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$classes = $stmt->get_result();
?>

<div class="container mt-5">
    <h2>Manage Classes</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Class Name</label>
            <input type="text" name="class_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Class</button>
    </form>

    <h3 class="mt-5">Your Classes</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Class Name</th>
                <th>Created On</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $classes->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

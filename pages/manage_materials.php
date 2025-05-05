<?php
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only teachers and admins can access this page
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'teacher' && $_SESSION['role'] != 'admin')) {
    header("Location: ../auth/login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

// Fetch learning materials uploaded by this teacher/admin
$materialsQuery = "SELECT id, title, file_path, file_type, uploaded_at FROM learning_materials WHERE uploaded_by = ?";
$stmt = $conn->prepare($materialsQuery);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$materials = $stmt->get_result();
?>

<div class="container mt-5">
    <h2 class="text-center">Manage Learning Materials</h2>
    
    <a href="upload_material.php" class="btn btn-primary mb-3">Upload New Material</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>File</th>
                <th>Type</th>
                <th>Uploaded At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($material = $materials->fetch_assoc()): ?>
            <tr>
                <td><?php echo $material['id']; ?></td>
                <td><?php echo htmlspecialchars($material['title']); ?></td>
                <td>
                    <a href="<?php echo BASE_URL . 'uploads/' . $material['file_path']; ?>" target="_blank">
                        View
                    </a>
                </td>
                <td><?php echo strtoupper($material['file_type']); ?></td>
                <td><?php echo $material['uploaded_at']; ?></td>
                <td>
                    <a href="edit_material.php?id=<?php echo $material['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_material.php?id=<?php echo $material['id']; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this material?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

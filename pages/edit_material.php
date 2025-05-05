<?php
include '../config.php';
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

// Check if ID is provided for editing
if (isset($_GET['id'])) {
    $material_id = $_GET['id'];
    $query = "SELECT title, file_path, type FROM learning_materials WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
}

// Handle form submission for updating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $newFile = $_FILES['file'];

    $updateQuery = "UPDATE learning_materials SET title = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $title, $material_id);
    $stmt->execute();

    if ($newFile['error'] === 0) {
        $file_name = time() . "_" . basename($newFile['name']);
        $target_file = "../uploads/" . $file_name;
        move_uploaded_file($newFile['tmp_name'], $target_file);

        $updateFileQuery = "UPDATE learning_materials SET file_path = ?, type = ? WHERE id = ?";
        $stmt = $conn->prepare($updateFileQuery);
        $stmt->bind_param("ssi", $file_name, pathinfo($file_name, PATHINFO_EXTENSION), $material_id);
        $stmt->execute();
    }

    header("Location: " . BASE_URL . "pages/manage_materials.php");
    exit();
}
?>

<div class="container mt-5">
    <h2 class="text-center">Edit Learning Material</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($material['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Current File</label>
            <p><a href="<?php echo BASE_URL . 'uploads/' . $material['file_path']; ?>" target="_blank">View Current File</a></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload New File (Optional)</label>
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Material</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

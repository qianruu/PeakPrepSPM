<?php
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only logged-in users can access
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user details
$query = "SELECT username, email, full_name, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Handle profile picture upload
    $profile_picture = $user['profile_picture'];
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../assets/profile_pictures/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = basename($_FILES["profile_picture"]["name"]);
    }

    $updateQuery = "UPDATE users SET full_name=?, email=?, profile_picture=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $full_name, $email, $profile_picture, $user_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
        header("Refresh: 2; URL=profile.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating profile.</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Update Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
            <?php if ($user['profile_picture']): ?>
                <img src="../assets/profile_pictures/<?php echo htmlspecialchars($user['profile_picture']); ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

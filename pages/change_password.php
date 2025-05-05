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

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password hash
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        echo "<div class='alert alert-danger'>Current password is incorrect.</div>";
    } elseif ($new_password !== $confirm_password) {
        echo "<div class='alert alert-danger'>New passwords do not match.</div>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE users SET password=? WHERE id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Password updated successfully!</div>";
            header("Refresh: 2; URL=profile.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error updating password.</div>";
        }
    }
}
?>

<div class="container mt-5">
    <h2>Change Password</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger">Change Password</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

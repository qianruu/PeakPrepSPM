<?php
include '../config.php';
include '../includes/header.php';
include '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Ensure only admin can perform this action
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT username, email, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $updateQuery = "UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssi", $username, $email, $role, $password, $user_id);
    } else {
        $updateQuery = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $username, $email, $role, $user_id);
    }
    
    if ($stmt->execute()) {
        header("Location: " . BASE_URL . "pages/manage_users.php");
        exit();
    } else {
        echo "Error updating user.";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Edit User</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-control" required>
                <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="teacher" <?php echo $user['role'] == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password (Leave blank to keep current password)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mb-3">Update User</button>
    </form>
</div>

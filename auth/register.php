<?php
include '../includes/header.php';
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center'>Registration successful! <a href='" . BASE_URL . "auth/login.php'>Login here</a></div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="container mt-5">
    <div class="row align-items-center justify-content-center">
        <!-- Left Side - Image -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="image-container" style="height: 100%; display: flex; align-items: center;">
                <img src="<?php echo BASE_URL; ?>assets/images/register.png" class="img-fluid rounded shadow" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="col-lg-6">
            <div class="card shadow-lg p-5" style="border-radius: 15px; background: linear-gradient(to right, #ffffff, #f8f9fa);">
                <h2 class="text-center mb-4" style="font-weight: 700; color: #333;">Sign Up for PeakPrepSPM</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control rounded-pill" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control rounded-pill" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control rounded-pill" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control rounded-pill">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill" style="font-size: 18px; font-weight: 600; letter-spacing: 0.5px;">Sign Up</button>
                </form>
                <p class="text-center mt-3" style="font-size: 16px;">Already have an account? <a href="<?php echo BASE_URL; ?>auth/login.php" style="color: #007bff; font-weight: 600;">Login</a></p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

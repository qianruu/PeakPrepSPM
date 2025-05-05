<?php
ob_start(); // Start output buffering
include '../includes/header.php';
include '../includes/db.php';
include '../config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in and redirect accordingly
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'student') {
        header("Location: " . BASE_URL . "pages/dashboard_student.php");
        exit();
    } elseif ($_SESSION['role'] == 'teacher') {
        header("Location: " . BASE_URL . "pages/dashboard_teacher.php");
        exit();
    } elseif ($_SESSION['role'] == 'admin') {
        header("Location: " . BASE_URL . "pages/admin_dashboard.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // ✅ Log User Login Activity
            logActivity($conn, $_SESSION['user_id'], "Logged in");

            // Redirect to correct dashboard
            if ($user['role'] == 'student') {
                header("Location: " . BASE_URL . "pages/dashboard_student.php");
            } elseif ($user['role'] == 'teacher') {
                header("Location: " . BASE_URL . "pages/dashboard_teacher.php");
            } elseif ($user['role'] == 'admin') {
                header("Location: " . BASE_URL . "pages/admin_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="image-container" style="height: 100%; display: flex; align-items: center;">
                <img src="<?php echo BASE_URL; ?>assets/images/login.jpg" class="img-fluid rounded shadow" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow-lg p-5" style="border-radius: 15px; background: linear-gradient(to right, #ffffff, #f8f9fa);">
                <h2 class="text-center mb-4" style="font-weight: 700; color: #333;">Login to PeakPrepSPM</h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control rounded-pill" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control rounded-pill" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill" style="font-size: 18px; font-weight: 600; letter-spacing: 0.5px;">Login</button>
                </form>
                <p class="text-center mt-3" style="font-size: 16px;">Don't have an account? <a href="<?php echo BASE_URL; ?>auth/register.php" style="color: #007bff; font-weight: 600;">Sign Up</a></p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

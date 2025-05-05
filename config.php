<?php
// Configuration file for PeakPrepSPM

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peakprep_spm";

// Define Base URL dynamically if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/PeakPrepSPM/');
}

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4
$conn->set_charset("utf8mb4");

// Prevent function redeclaration
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('loginUser')) {
    function loginUser($user_id, $username, $role) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: " . BASE_URL . "pages/dashboard.php");
        exit();
    }
}

if (!function_exists('logoutUser')) {
    function logoutUser() {
        session_destroy();
        header("Location: " . BASE_URL . "auth/login.php");
        exit();
    }
}

if (!function_exists('redirectToDashboard')) {
    function redirectToDashboard() {
        if (!isLoggedIn()) {
            header("Location: " . BASE_URL . "auth/login.php");
            exit();
        }
        $role = $_SESSION['role'];
        if ($role == 'student') {
            header("Location: " . BASE_URL . "pages/dashboard_student.php");
        } elseif ($role == 'teacher') {
            header("Location: " . BASE_URL . "pages/dashboard_teacher.php");
        } elseif ($role == 'admin') {
            header("Location: " . BASE_URL . "pages/admin_dashboard.php");
        }
        exit();
    }
}

// Prevent function redeclaration
if (!function_exists('logActivity')) {
    function logActivity($conn, $user_id, $action) {
        $sql = "INSERT INTO activity_log (user_id, action) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $action);
        $stmt->execute();
    }
}

// Load and Apply Theme Preference
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT theme FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $_SESSION['theme'] = $user['theme'] ?? 'light';
} else {
    $_SESSION['theme'] = 'light'; // Default theme for guests
}
?>

<?php
ob_start(); // Start output buffering
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include dirname(__DIR__) . "/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PeakPrepSPM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Navbar Custom Styling */
        .navbar {
            background: linear-gradient(90deg, #1a1a2e, #16213e);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-right: 50px; /* Increased space between logo and menu */
        }

        .navbar-nav {
            margin: 0 auto; /* Center menu items */
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .navbar-nav .nav-item {
            margin: 0 12px; /* Space between menu items */
        }

        .nav-link {
            font-size: 1rem;
            font-weight: 600;
        }

        /* Mobile View Fixes */
        @media (max-width: 992px) {
            .navbar-nav {
                text-align: center;
                width: 100%;
            }

            .navbar-toggler {
                background-color: white !important;
                border: none;
            }

            .navbar-toggler-icon {
                filter: invert(1);
            }

            .search-container {
                margin-top: 10px;
            }
        }

        /* Right Section Styling */
        .search-container {
            display: flex;
            align-items: center;
            gap: 10px; /* Reduced space to bring elements closer */
        }

        .search-container .form-control {
            width: 200px;
            border-radius: 50px;
        }

        .login-register {
            display: flex;
            align-items: center;
            gap: 8px; /* Closer spacing between Login & Register */
        }

        .dark-mode-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: transparent;
            border: 1px solid white;
            color: white;
            font-size: 1.2rem;
        }

        .dark-mode-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="<?php echo $_SESSION['theme'] == 'dark' ? 'dark-mode' : ''; ?>">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Brand Logo -->
        <a class="navbar-brand text-white" href="<?php echo BASE_URL; ?>">
            PeakPrepSPM
        </a>

        <!-- Mobile Menu Toggler (White Button) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <!-- Centered Navigation Menu -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="<?php echo BASE_URL; ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?php echo BASE_URL; ?>pages/about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?php echo BASE_URL; ?>pages/contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Dashboard Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="dashboardDropdown" role="button" data-bs-toggle="dropdown">
                            Dashboard
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/admin_dashboard.php">Admin Dashboard</a></li>
                            <?php elseif ($_SESSION['role'] == 'student'): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/dashboard_student.php">Student Dashboard</a></li>
                            <?php elseif ($_SESSION['role'] == 'teacher'): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/dashboard_teacher.php">Teacher Dashboard</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <!-- Pages Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown">
                            Pages
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/change_password.php">Change Password</a></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>auth/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <div class="login-register">
                        <a class="nav-link text-white" href="<?php echo BASE_URL; ?>auth/login.php">Login</a>
                        <a class="nav-link text-white" href="<?php echo BASE_URL; ?>auth/register.php">Register</a>
                    </div>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Right Section: Search & Dark Mode -->
        <div class="search-container">
            <form class="input-group">
                <input class="form-control" type="search" placeholder="Search">
                <button class="btn btn-warning rounded-end-pill" type="submit">🔍</button>
            </form>

            <!-- Dark Mode Toggle (Icon Only) -->
            <button id="theme-toggle" class="dark-mode-toggle">
                <span id="theme-icon"><?php echo $_SESSION['theme'] == 'dark' ? '☀' : '🌙'; ?></span>
            </button>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const themeToggle = document.getElementById("theme-toggle");
    const themeIcon = document.getElementById("theme-icon");

    themeToggle.addEventListener("click", function () {
        let newTheme = document.body.classList.contains("dark-mode") ? "light" : "dark";
        document.body.classList.toggle("dark-mode");
        themeIcon.innerText = newTheme === "dark" ? "☀" : "🌙";

        fetch("<?php echo BASE_URL; ?>pages/update_theme.php?theme=" + newTheme)
            .then(response => response.text())
            .then(data => console.log("Theme Updated:", data));
    });

    if ("<?php echo $_SESSION['theme']; ?>" === "dark") {
        document.body.classList.add("dark-mode");
    }
});
</script>

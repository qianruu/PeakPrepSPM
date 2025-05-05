<?php
session_start();
include '../config.php'; // Ensure BASE_URL is defined
session_unset();
session_destroy();
header("Location: " . BASE_URL . "auth/login.php");
exit();
?>

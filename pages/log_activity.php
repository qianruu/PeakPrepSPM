<?php
include '../config.php';
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    logActivity($conn, $user_id, $action);
}
?>

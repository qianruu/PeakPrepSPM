<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] == 'dark') ? 'light' : 'dark';
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>

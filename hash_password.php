<?php
$adminPassword = "Admin@123";
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
echo "Hashed Password: " . $hashedPassword;
?>

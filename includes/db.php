<?php
// Database connection file

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peakprep_spm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding
$conn->set_charset("utf8mb4");

?>

<?php
// Database Configuration
// Update these with your cPanel database credentials

$db_host = 'localhost';
$db_user = 'diyproject_user';      // Change this to your cPanel database user
$db_pass = 'your_password_here';   // Change this to your cPanel database password
$db_name = 'diyproject_cms';       // Change this to your cPanel database name

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
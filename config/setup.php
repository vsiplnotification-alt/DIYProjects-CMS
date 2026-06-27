<?php
// Database Setup - Run this once to create tables
include 'db.php';

// Create projects table
$projects_sql = "CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

// Create certificates table
$certificates_sql = "CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    certificate_number VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    payment_id VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

// Create admins table
$admins_sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $projects_sql)) {
    echo "Projects table created successfully<br>";
} else {
    echo "Error creating projects table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $certificates_sql)) {
    echo "Certificates table created successfully<br>";
} else {
    echo "Error creating certificates table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $admins_sql)) {
    echo "Admins table created successfully<br>";
} else {
    echo "Error creating admins table: " . mysqli_error($conn) . "<br>";
}

// Create default admin user (change this password!)
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$email = 'admin@diyprojects.co.in';

$check_admin = "SELECT * FROM admins WHERE username = 'admin'";
$result = mysqli_query($conn, $check_admin);

if (mysqli_num_rows($result) == 0) {
    $insert_admin = "INSERT INTO admins (username, password, email) VALUES ('$username', '$password', '$email')";
    if (mysqli_query($conn, $insert_admin)) {
        echo "<br><strong>Default admin created!</strong><br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "<strong>Please change this password after login!</strong>";
    } else {
        echo "Error creating admin: " . mysqli_error($conn);
    }
} else {
    echo "Admin user already exists<br>";
}

mysqli_close($conn);
?>
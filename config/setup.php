<?php
// Database Setup - Run this once to create tables
include 'db.php';

echo "<h2>DIY Projects CMS - Database Setup</h2>";
echo "<hr>";

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

// Execute queries with better error handling
if (mysqli_query($conn, $projects_sql)) {
    echo "<div style='color: green;'><strong>✅ Projects table created successfully</strong></div>";
} else {
    echo "<div style='color: red;'><strong>❌ Error creating projects table:</strong> " . mysqli_error($conn) . "</div>";
}

if (mysqli_query($conn, $certificates_sql)) {
    echo "<div style='color: green;'><strong>✅ Certificates table created successfully</strong></div>";
} else {
    echo "<div style='color: red;'><strong>❌ Error creating certificates table:</strong> " . mysqli_error($conn) . "</div>";
}

if (mysqli_query($conn, $admins_sql)) {
    echo "<div style='color: green;'><strong>✅ Admins table created successfully</strong></div>";
} else {
    echo "<div style='color: red;'><strong>❌ Error creating admins table:</strong> " . mysqli_error($conn) . "</div>";
}

echo "<hr>";

// Create default admin user (change this password!)
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$email = 'admin@diyprojects.co.in';

$check_admin = "SELECT * FROM admins WHERE username = 'admin'";
$result = mysqli_query($conn, $check_admin);

if (mysqli_num_rows($result) == 0) {
    $insert_admin = "INSERT INTO admins (username, password, email) VALUES ('$username', '$password', '$email')";
    if (mysqli_query($conn, $insert_admin)) {
        echo "<div style='color: green;'><strong>✅ Default admin user created!</strong></div>";
        echo "<p><strong>Admin Credentials:</strong></p>";
        echo "<ul>";
        echo "<li>Username: <code>admin</code></li>";
        echo "<li>Password: <code>admin123</code></li>";
        echo "</ul>";
        echo "<p style='color: red;'><strong>⚠️ IMPORTANT: Change this password after login!</strong></p>";
    } else {
        echo "<div style='color: red;'><strong>❌ Error creating admin:</strong> " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div style='color: blue;'><strong>ℹ️ Admin user already exists</strong></div>";
}

echo "<hr>";
echo "<p><strong>Setup Complete!</strong></p>";
echo "<p><a href='../admin/login.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Admin Login</a></p>";

mysqli_close($conn);
?>

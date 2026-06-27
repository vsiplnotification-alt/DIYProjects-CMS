<?php
// Certificates Management
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fetch all certificates
$query = "SELECT c.*, p.title FROM certificates c 
          JOIN projects p ON c.project_id = p.id 
          ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $query);
$certificates = [];
while ($row = mysqli_fetch_assoc($result)) {
    $certificates[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates - DIY Projects Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container">
            <div class="logo">DIYProjects Admin</div>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="projects.php">Projects</a></li>
                <li><a href="certificates.php" class="active">Certificates</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <h1>Certificate Registrations</h1>

            <div class="certificates-table">
                <table>
                    <thead>
                        <tr>
                            <th>Certificate No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Project</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($certificates as $cert): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cert['certificate_number']); ?></td>
                            <td><?php echo htmlspecialchars($cert['name']); ?></td>
                            <td><?php echo htmlspecialchars($cert['email']); ?></td>
                            <td><?php echo htmlspecialchars($cert['contact']); ?></td>
                            <td><?php echo htmlspecialchars($cert['title']); ?></td>
                            <td><?php echo date('d M Y', strtotime($cert['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>
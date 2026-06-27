<?php
// Admin Dashboard
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fetch all projects
$query = "SELECT * FROM projects ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$projects = [];
while ($row = mysqli_fetch_assoc($result)) {
    $projects[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DIY Projects</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container">
            <div class="logo">DIYProjects Admin</div>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="projects.php">Projects</a></li>
                <li><a href="certificates.php">Certificates</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <div class="dashboard-header">
                <h1>Dashboard</h1>
                <a href="add-project.php" class="btn btn-primary">Add New Project</a>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Projects</h3>
                    <p class="stat-number"><?php echo count($projects); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Certificates</h3>
                    <p class="stat-number">
                        <?php 
                        $cert_query = "SELECT COUNT(*) as count FROM certificates";
                        $cert_result = mysqli_query($conn, $cert_query);
                        $cert_data = mysqli_fetch_assoc($cert_result);
                        echo $cert_data['count'];
                        ?>
                    </p>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="projects-table">
                <h2>Your Projects</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($projects as $project): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($project['title']); ?></td>
                            <td><?php echo date('d M Y', strtotime($project['created_at'])); ?></td>
                            <td><span class="badge badge-success">Published</span></td>
                            <td>
                                <a href="edit-project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="delete-project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this project?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>
<?php
// DIY Projects CMS - Public Home Page
session_start();
include 'config/db.php';

// Fetch all projects
$query = "SELECT * FROM projects ORDER BY created_at DESC LIMIT 4";
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
    <title>DIY Projects - Free IoT & Embedded Systems Tutorials</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">DIYProjects</div>
            <ul class="nav-menu">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="#projects">Tutorials</a></li>
                <?php if(isset($_SESSION['admin'])): ?>
                    <li><a href="admin/dashboard.php">Admin</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="admin/login.php">Admin Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Learn Embedded IoT Development</h1>
            <p>Free tutorials to master IoT and Embedded Systems</p>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="projects-section">
        <div class="container">
            <h2>Featured Tutorials</h2>
            <div class="projects-grid">
                <?php foreach($projects as $project): ?>
                <div class="project-card">
                    <div class="project-image">
                        <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                    </div>
                    <div class="project-content">
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($project['description'], 0, 100)) . '...'; ?></p>
                    </div>
                    <div class="project-footer">
                        <a href="tutorial.php?id=<?php echo $project['id']; ?>" class="btn btn-primary">Get Tutorial</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 DIY Projects. All rights reserved. | Domain: diyprojects.co.in</p>
        </div>
    </footer>
</body>
</html>
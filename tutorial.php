<?php
// DIY Projects CMS - Tutorial Page
session_start();
include 'config/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$project_id = intval($_GET['id']);
$query = "SELECT * FROM projects WHERE id = $project_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$project = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - DIY Projects</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">DIYProjects</div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#projects">Tutorials</a></li>
                <?php if(isset($_SESSION['admin'])): ?>
                    <li><a href="admin/dashboard.php">Admin</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Tutorial Content -->
    <section class="tutorial-section">
        <div class="container">
            <div class="tutorial-header">
                <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="tutorial-image">
                <h1><?php echo htmlspecialchars($project['title']); ?></h1>
            </div>

            <div class="tutorial-content">
                <?php echo $project['content']; ?>
            </div>

            <!-- Certificate Section -->
            <section class="certificate-section">
                <div class="certificate-box">
                    <h2>Get Your Certificate</h2>
                    <p>Complete this tutorial and register to receive your certificate of completion!</p>
                    <button class="btn btn-success" onclick="openCertificateForm()">Register for Certificate</button>
                </div>
            </section>
        </div>
    </section>

    <!-- Certificate Modal -->
    <div id="certificateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCertificateForm()">&times;</span>
            <h2>Certificate Registration</h2>
            <form id="certificateForm" method="POST" action="process-certificate.php">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number *</label>
                    <input type="tel" id="contact" name="contact" required>
                </div>

                <div class="form-group">
                    <label for="payment_id">Payment ID *</label>
                    <input type="text" id="payment_id" name="payment_id" placeholder="Enter UPI/Payment ID" required>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 DIY Projects. All rights reserved. | Domain: diyprojects.co.in</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
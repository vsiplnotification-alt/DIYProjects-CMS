<?php
// Certificate Success Page
session_start();
include 'config/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$cert_id = intval($_GET['id']);
$query = "SELECT * FROM certificates WHERE id = $cert_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$certificate = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - DIY Projects</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">DIYProjects</div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
            </ul>
        </div>
    </nav>

    <section class="certificate-page">
        <div class="container">
            <div class="certificate-container">
                <div class="certificate">
                    <div class="certificate-header">
                        <h1>Certificate of Completion</h1>
                    </div>
                    <div class="certificate-body">
                        <p>This is to certify that</p>
                        <h2><?php echo htmlspecialchars($certificate['name']); ?></h2>
                        <p>has successfully completed the IoT and Embedded Systems tutorial</p>
                        <p class="cert-number">Certificate Number: <?php echo htmlspecialchars($certificate['certificate_number']); ?></p>
                        <p class="cert-date">Date: <?php echo date('d M Y', strtotime($certificate['created_at'])); ?></p>
                    </div>
                </div>
                <div class="certificate-actions">
                    <button onclick="window.print()" class="btn btn-primary">Print Certificate</button>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 DIY Projects. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
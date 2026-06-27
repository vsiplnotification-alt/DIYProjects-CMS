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
                    <h2>🎓 Get Your Certificate</h2>
                    <p>Complete this tutorial and get your certificate of completion!</p>
                    <p style="color: #e74c3c; font-weight: bold;">Certificate Fee: ₹200</p>
                    <button class="btn btn-success" onclick="openCertificateForm()">Register for Certificate</button>
                </div>
            </section>
        </div>
    </section>

    <!-- Certificate Modal -->
    <div id="certificateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCertificateForm()">&times;</span>
            <h2>📋 Certificate Registration</h2>
            
            <form id="certificateForm" class="certificate-form">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required placeholder="your@email.com">
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number *</label>
                    <input type="tel" id="contact" name="contact" required placeholder="10-digit mobile number">
                </div>

                <div class="certificate-details">
                    <p><strong>Certificate Fee:</strong> ₹200</p>
                    <p><small>Click "Proceed to Payment" to continue with Razorpay payment</small></p>
                </div>

                <button type="button" class="btn btn-primary" onclick="proceedToPayment()">
                    💳 Proceed to Payment (₹200)
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeCertificateForm()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Hidden Form for Razorpay Payment Button -->
    <form id="razorpayForm" method="POST" action="payment-success.php" style="display: none;">
        <input type="hidden" id="hidden_project_id" name="project_id">
        <input type="hidden" id="hidden_name" name="name">
        <input type="hidden" id="hidden_email" name="email">
        <input type="hidden" id="hidden_contact" name="contact">
        <script src="https://checkout.razorpay.com/v1/payment-button.js" data-payment_button_id="pl_T6e5T6MXTuxZ8Z" async> </script> 
    </form>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 DIY Projects. All rights reserved. | Domain: diyprojects.co.in</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script>
        function proceedToPayment() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const contact = document.getElementById('contact').value.trim();
            const project_id = document.querySelector('input[name="project_id"]').value;

            // Validation
            if (!name || !email || !contact) {
                alert('Please fill in all fields');
                return;
            }

            // Validate phone (10 digits)
            if (!/^\d{10}$/.test(contact.replace(/[^\d]/g, ''))) {
                alert('Please enter a valid 10-digit contact number');
                return;
            }

            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address');
                return;
            }

            // Store data in hidden form and submit to Razorpay button
            document.getElementById('hidden_project_id').value = project_id;
            document.getElementById('hidden_name').value = name;
            document.getElementById('hidden_email').value = email;
            document.getElementById('hidden_contact').value = contact;

            // Click the Razorpay payment button
            const paymentButton = document.querySelector('[data-payment_button_id="pl_T6e5T6MXTuxZ8Z"]');
            if (paymentButton) {
                paymentButton.click();
            }

            // Alternative: Submit the form directly
            // document.getElementById('razorpayForm').submit();
        }

        function openCertificateForm() {
            document.getElementById('certificateModal').style.display = 'block';
        }

        function closeCertificateForm() {
            document.getElementById('certificateModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('certificateModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

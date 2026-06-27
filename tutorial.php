<?php
// DIY Projects CMS - Tutorial Page
session_start();
include 'config/db.php';
include 'config/razorpay.php';

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

// Generate unique order ID
$order_id = 'ORD-' . time() . '-' . rand(1000, 9999);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - DIY Projects</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                
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
                    <p><small>You will be redirected to Razorpay for secure payment</small></p>
                </div>

                <button type="button" class="btn btn-primary" onclick="initiatePayment()">
                    💳 Proceed to Payment (₹200)
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeCertificateForm()">Cancel</button>
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
    <script>
        // Razorpay Payment Integration
        function initiatePayment() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const contact = document.getElementById('contact').value.trim();
            const project_id = document.querySelector('input[name="project_id"]').value;
            const order_id = document.querySelector('input[name="order_id"]').value;

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

            // Razorpay Options
            const options = {
                key: '<?php echo RAZORPAY_KEY_ID; ?>',
                amount: <?php echo CERTIFICATE_FEE; ?>,
                currency: '<?php echo CERTIFICATE_CURRENCY; ?>',
                name: 'DIY Projects',
                description: '<?php echo CERTIFICATE_DESCRIPTION; ?>',
                order_id: order_id,
                handler: function(response) {
                    handlePaymentSuccess(response, name, email, contact, project_id);
                },
                prefill: {
                    name: name,
                    email: email,
                    contact: contact
                },
                theme: {
                    color: '#3498db'
                },
                modal: {
                    ondismiss: function() {
                        console.log('Payment cancelled');
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }

        function handlePaymentSuccess(response, name, email, contact, project_id) {
            // Send payment details to backend for verification
            const formData = new FormData();
            formData.append('razorpay_payment_id', response.razorpay_payment_id);
            formData.append('razorpay_order_id', response.razorpay_order_id);
            formData.append('razorpay_signature', response.razorpay_signature);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('contact', contact);
            formData.append('project_id', project_id);

            fetch('razorpay-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Certificate registered successfully');
                    // Redirect to certificate download
                    window.location.href = data.redirect;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
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

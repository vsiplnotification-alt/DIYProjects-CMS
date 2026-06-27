<?php
// Payment Success Page - After Razorpay Payment
session_start();
include 'config/db.php';

$certificate = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = intval($_POST['project_id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $contact = mysqli_real_escape_string($conn, $_POST['contact'] ?? '');
    
    // Validate inputs
    if (empty($project_id) || empty($name) || empty($email) || empty($contact)) {
        $error = "Missing required information. Please try again.";
    } else {
        // Generate payment ID (since we're using payment button, we'll use timestamp)
        $payment_id = 'PAY-' . time() . '-' . rand(1000, 9999);
        
        // Insert certificate registration with 'pending' status
        $query = "INSERT INTO certificates (project_id, name, email, contact, payment_id, status) 
                  VALUES ($project_id, '$name', '$email', '$contact', '$payment_id', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            $cert_id = mysqli_insert_id($conn);
            $certificate_number = 'DIY-' . date('Y') . '-' . str_pad($cert_id, 5, '0', STR_PAD_LEFT);
            
            // Update with certificate number
            mysqli_query($conn, "UPDATE certificates SET certificate_number = '$certificate_number' WHERE id = $cert_id");
            
            // Fetch the updated certificate
            $fetch_query = "SELECT c.*, p.title FROM certificates c 
                           JOIN projects p ON c.project_id = p.id 
                           WHERE c.id = $cert_id";
            $result = mysqli_query($conn, $fetch_query);
            $certificate = mysqli_fetch_assoc($result);
            
            error_log("Certificate registered - ID: $cert_id, Payment: $payment_id, Status: pending");
        } else {
            $error = "Failed to register certificate: " . mysqli_error($conn);
            error_log("Failed to register certificate: " . mysqli_error($conn));
        }
    }
} else {
    $error = "Invalid request. Please go back and try again.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - DIY Projects</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .success-section {
            padding: 3rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .success-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .success-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .success-icon {
            font-size: 60px;
            margin-bottom: 1rem;
        }

        .success-header h1 {
            color: #27ae60;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .success-header p {
            color: #666;
            font-size: 16px;
            margin: 0.5rem 0;
        }

        .payment-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            border-left: 4px solid #27ae60;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #ddd;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .detail-value {
            color: #666;
            word-break: break-word;
            text-align: right;
        }

        .certificate-info {
            background: #e8f8f5;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            border: 2px solid #27ae60;
        }

        .certificate-info h3 {
            color: #27ae60;
            margin-top: 0;
        }

        .certificate-info p {
            margin: 0.5rem 0;
            color: #333;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 0.5rem;
        }

        .next-steps {
            background: #d1ecf1;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            border-left: 4px solid #0c5460;
        }

        .next-steps h3 {
            color: #0c5460;
            margin-top: 0;
        }

        .next-steps ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
            color: #0c5460;
        }

        .next-steps li {
            margin: 0.5rem 0;
        }

        .actions {
            text-align: center;
            margin-top: 2rem;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            font-size: 14px;
            font-weight: bold;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .error-container {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .error-container h3 {
            margin-top: 0;
        }

        .certificate-number {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #27ae60;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">DIYProjects</div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#projects">Tutorials</a></li>
            </ul>
        </div>
    </nav>

    <!-- Success Section -->
    <section class="success-section">
        <div class="success-container">
            <?php if ($error): ?>
                <!-- Error Message -->
                <div class="error-container">
                    <h3>❌ Error</h3>
                    <p><?php echo htmlspecialchars($error); ?></p>
                    <a href="index.php" class="btn btn-secondary" style="margin-top: 1rem;">← Back to Home</a>
                </div>

            <?php elseif ($certificate): ?>
                <!-- Success Message -->
                <div class="success-header">
                    <div class="success-icon">✅</div>
                    <h1>Payment Received!</h1>
                    <p>Your certificate registration has been submitted successfully</p>
                </div>

                <!-- Payment Details -->
                <div class="payment-details">
                    <div class="detail-row">
                        <span class="detail-label">Registration Status:</span>
                        <span class="status-badge">⏳ Pending Verification</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Certificate Number:</span>
                        <span class="detail-value certificate-number"><?php echo htmlspecialchars($certificate['certificate_number']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Recipient Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['email']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Contact:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['contact']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Course:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['title']); ?></span>
                    </div>
                </div>

                <!-- Certificate Info -->
                <div class="certificate-info">
                    <h3>📋 About Your Certificate</h3>
                    <p>Your payment has been received. Your certificate request is now <strong>pending manual verification</strong>.</p>
                    <p>Once verified, your certificate will be emailed to you at <strong><?php echo htmlspecialchars($certificate['email']); ?></strong></p>
                </div>

                <!-- Next Steps -->
                <div class="next-steps">
                    <h3>📝 What Happens Next?</h3>
                    <ol>
                        <li>Your certificate request has been submitted with ID: <strong><?php echo htmlspecialchars($certificate['certificate_number']); ?></strong></li>
                        <li>Our team will manually verify your certificate within 24-48 hours</li>
                        <li>Once verified, your certificate will be sent to your email</li>
                        <li>You can verify your certificate anytime using the verification page</li>
                    </ol>
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="verify-certificate.php" class="btn">🔍 Verify Certificate</a>
                    <a href="index.php" class="btn btn-secondary">🏠 Back to Home</a>
                </div>

            <?php else: ?>
                <!-- Default - No data -->
                <div class="error-container">
                    <h3>⚠️ No Data Found</h3>
                    <p>Unable to process your request. Please try again.</p>
                    <a href="index.php" class="btn btn-secondary" style="margin-top: 1rem;">← Back to Home</a>
                </div>
            <?php endif; ?>
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

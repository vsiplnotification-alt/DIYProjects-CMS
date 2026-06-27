<?php
// Certificate Download - Generate PDF
session_start();
include 'config/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$cert_id = intval($_GET['id']);
$query = "SELECT c.*, p.title FROM certificates c 
          JOIN projects p ON c.project_id = p.id 
          WHERE c.id = $cert_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$certificate = mysqli_fetch_assoc($result);
$logo_url = 'https://diyprojects.co.in/assets/images/logo.png';

// Create a unique verification QR code URL (you can use this for verification)
$verification_url = 'https://diyprojects.co.in/verify-certificate.php?cert=' . $certificate['certificate_number'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo htmlspecialchars($certificate['name']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate-wrapper {
            max-width: 900px;
            width: 100%;
        }

        .certificate {
            background: white;
            padding: 50px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            position: relative;
            border: 3px solid #3498db;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(52, 152, 219, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(52, 152, 219, 0.05) 0%, transparent 50%);
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
        }

        .logo-section {
            margin-bottom: 15px;
        }

        .logo-section img {
            height: 60px;
            width: auto;
            margin-bottom: 10px;
        }

        .certificate-title {
            font-size: 36px;
            color: #3498db;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 10px 0;
        }

        .certificate-subtitle {
            color: #666;
            font-size: 14px;
            font-style: italic;
        }

        .certificate-body {
            text-align: center;
            padding: 40px 20px;
        }

        .certificate-body p {
            font-size: 16px;
            margin: 15px 0;
            color: #333;
            line-height: 1.6;
        }

        .name-section {
            margin: 30px 0;
        }

        .recipient-name {
            font-size: 32px;
            color: #2c3e50;
            font-weight: bold;
            text-decoration: underline;
            text-decoration-style: double;
            text-decoration-color: #3498db;
            margin: 20px 0;
            letter-spacing: 1px;
        }

        .course-name {
            font-size: 18px;
            color: #2c3e50;
            font-weight: bold;
            margin: 20px 0;
            font-style: italic;
        }

        .certificate-details {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #eee;
            flex-wrap: wrap;
        }

        .detail-box {
            text-align: center;
            padding: 15px 20px;
            flex: 1;
            min-width: 200px;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .detail-value {
            font-size: 14px;
            color: #2c3e50;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            word-break: break-all;
        }

        .issue-date {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }

        .footer-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #999;
        }

        .verification-section {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .verification-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .verification-link {
            font-size: 11px;
            color: #3498db;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .badge {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
            font-weight: bold;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .certificate {
                box-shadow: none;
                border: 2px solid #3498db;
                padding: 40px;
            }

            .actions {
                display: none;
            }

            .verification-section {
                background: white;
                border: 1px dashed #999;
            }
        }

        @media (max-width: 768px) {
            .certificate {
                padding: 30px 20px;
            }

            .certificate-title {
                font-size: 28px;
            }

            .recipient-name {
                font-size: 24px;
            }

            .certificate-details {
                flex-direction: column;
            }

            .detail-box {
                border-bottom: 1px dashed #eee;
                padding: 15px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate">
            <!-- Certificate Header -->
            <div class="certificate-header">
                <div class="logo-section">
                    <img src="<?php echo $logo_url; ?>" alt="DIY Projects Logo" onerror="this.style.display='none'">
                </div>
                <div class="certificate-title">CERTIFICATE OF COMPLETION</div>
                <p class="certificate-subtitle">DIY Projects - IoT & Embedded Systems</p>
            </div>

            <!-- Certificate Body -->
            <div class="certificate-body">
                <p>This is to certify that</p>
                
                <div class="recipient-name"><?php echo htmlspecialchars($certificate['name']); ?></div>

                <p>has successfully completed the</p>
                
                <div class="course-name"><?php echo htmlspecialchars($certificate['title']); ?> Tutorial</div>

                <p>and demonstrated proficiency in IoT and Embedded Systems development.</p>

                <!-- Certificate Details -->
                <div class="certificate-details">
                    <div class="detail-box">
                        <div class="detail-label">Certificate Number</div>
                        <div class="detail-value"><?php echo htmlspecialchars($certificate['certificate_number']); ?></div>
                    </div>

                    <div class="detail-box">
                        <div class="detail-label">Issue Date</div>
                        <div class="detail-value"><?php echo date('d M Y', strtotime($certificate['created_at'])); ?></div>
                    </div>

                    <div class="detail-box">
                        <div class="detail-label">Verification ID</div>
                        <div class="detail-value" style="font-size: 12px;">DIY-<?php echo strtoupper(substr(hash('sha256', $certificate['certificate_number']), 0, 8)); ?></div>
                    </div>
                </div>

                <!-- Verification Section -->
                <div class="verification-section">
                    <div class="verification-label">Verify this certificate online:</div>
                    <div class="verification-link"><?php echo $verification_url; ?></div>
                    <div class="badge">✓ Verified</div>
                </div>

                <!-- Footer -->
                <div class="footer-section">
                    <p>This certificate is issued by DIY Projects (<strong>diyprojects.co.in</strong>) as recognition of successful completion of the tutorial course.</p>
                    <p style="margin-top: 10px;">
                        Issued on: <strong><?php echo date('d F Y', strtotime($certificate['created_at'])); ?></strong>
                    </p>
                    <p style="margin-top: 5px; font-size: 10px; color: #bbb;">
                        Certificate ID: <?php echo $certificate['id']; ?> | Recipient Email: <?php echo htmlspecialchars($certificate['email']); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="actions">
            <button class="btn" onclick="window.print()">🖨️ Print Certificate</button>
            <button class="btn" onclick="downloadPDF()">📥 Download as PDF</button>
            <a href="index.php" class="btn btn-secondary">🏠 Back to Home</a>
        </div>
    </div>

    <script>
        function downloadPDF() {
            // Simple method - users can use browser's Print to PDF
            alert('Use the Print button and select "Save as PDF" from your printer options for best results.');
            window.print();
        }
    </script>
</body>
</html>

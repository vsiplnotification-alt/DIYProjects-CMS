<?php
// Razorpay Payment Handler - Verify Payment
session_start();
include '../config/db.php';
include '../config/razorpay.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['razorpay_payment_id'] ?? '';
    $order_id = $_POST['razorpay_order_id'] ?? '';
    $signature = $_POST['razorpay_signature'] ?? '';
    
    // Reconstruct the signature to verify
    $data_to_verify = $order_id . '|' . $payment_id;
    $generated_signature = hash_hmac('sha256', $data_to_verify, RAZORPAY_KEY_SECRET);
    
    if ($generated_signature === $signature) {
        // Payment verified successfully
        $project_id = intval($_POST['project_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $payment_id_stored = mysqli_real_escape_string($conn, $payment_id);
        
        // Insert certificate registration with payment details
        $query = "INSERT INTO certificates (project_id, name, email, contact, payment_id, created_at) 
                  VALUES ($project_id, '$name', '$email', '$contact', '$payment_id_stored', NOW())";
        
        if (mysqli_query($conn, $query)) {
            $cert_id = mysqli_insert_id($conn);
            $certificate_number = 'DIY-' . date('Y') . '-' . str_pad($cert_id, 5, '0', STR_PAD_LEFT);
            
            // Update with certificate number
            mysqli_query($conn, "UPDATE certificates SET certificate_number = '$certificate_number' WHERE id = $cert_id");
            
            $response['status'] = 'success';
            $response['message'] = 'Payment verified! Your certificate is ready.';
            $response['cert_id'] = $cert_id;
            $response['redirect'] = 'certificate-download.php?id=' . $cert_id;
            
            error_log("Certificate registered - ID: $cert_id, Payment: $payment_id");
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to save certificate: ' . mysqli_error($conn);
            error_log("Failed to save certificate: " . mysqli_error($conn));
        }
    } else {
        // Payment verification failed
        $response['status'] = 'error';
        $response['message'] = 'Payment verification failed. Please contact support.';
        error_log("Invalid signature. Generated: $generated_signature, Received: $signature");
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>

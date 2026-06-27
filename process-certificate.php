<?php
// Process Certificate Registration
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = intval($_POST['project_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $payment_id = mysqli_real_escape_string($conn, $_POST['payment_id']);

    // Insert certificate registration
    $query = "INSERT INTO certificates (project_id, name, email, contact, payment_id, created_at) 
              VALUES ($project_id, '$name', '$email', '$contact', '$payment_id', NOW())";
    
    if (mysqli_query($conn, $query)) {
        // Generate certificate file
        $cert_id = mysqli_insert_id($conn);
        $certificate_number = 'DIY-' . date('Y') . '-' . str_pad($cert_id, 5, '0', STR_PAD_LEFT);
        
        // Update with certificate number
        mysqli_query($conn, "UPDATE certificates SET certificate_number = '$certificate_number' WHERE id = $cert_id");
        
        // Redirect to success page
        header('Location: certificate-success.php?id=' . $cert_id);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header('Location: index.php');
    exit();
}
<?php
// Delete Project
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $project_id = intval($_GET['id']);
    $query = "DELETE FROM projects WHERE id = $project_id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: dashboard.php?success=Project deleted successfully');
    } else {
        header('Location: dashboard.php?error=Failed to delete project');
    }
    exit();
} else {
    header('Location: dashboard.php');
    exit();
}
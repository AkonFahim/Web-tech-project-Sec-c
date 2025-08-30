<?php
session_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if email and password fields exist
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if ($email == "" || $password == "") {
            // Redirect back with error
            header('location: ../View/login.php?error=empty_fields');
            exit();
        } else {
            // Validate credentials - replace with your actual authentication logic
            if ($email == "admin@gmail.com" && $password == "admin1") {
                // Valid user
                setcookie('status', 'true', time() + 3000, '/');
                $_SESSION['email'] = $email; 
                header('location: ../View/dashboard.php');
                exit();
            } else {
                header('location: ../View/login.php?error=invalid_user');
                exit();
            }
        }
    } else {
        // Missing required fields
        header('location: ../View/login.php?error=badrequest');
        exit();
    }
} else {
    // If someone tries to access this page directly
    header('location: ../View/login.php?error=badrequest');
    exit();
}
?>
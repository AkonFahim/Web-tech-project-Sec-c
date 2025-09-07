<?php
session_start();

if(isset($_SESSION['user_id'])) {
    header('location: dashboard.php');
    exit();
}

include '../Controller/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        header('location: ../View/forgotpassword.php?error=empty_email');
        exit();
    }
    
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        if ($user['status'] === 'Blocked') {
            mysqli_close($con);
            header('location: ../View/errors/blockeduser.php');
            exit();
        }
        
        mysqli_close($con);
        header('location: ../View/forgotpassword.php?success=reset_sent');
        exit();
    } else {
        mysqli_close($con);
        header('location: ../View/forgotpassword.php?error=invalid_email');
        exit();
    }
} else {
    mysqli_close($con);
    header('location: ../View/forgotpassword.php?error=server_error');
    exit();
}
?>
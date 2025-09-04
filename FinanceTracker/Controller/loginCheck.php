<?php
session_start();
include '../Model/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']) ? true : false;
    
    if (empty($email) || empty($password)) {
        mysqli_close($con);
        header('location: ../View/login.php?error=empty_fields');
        exit();
    }
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        if ($user['status'] === 'Blocked') {
            mysqli_close($con);
            header('location: ../View/errors/blockeduser.php');
            exit();
        }
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            
            if ($remember) {
                setcookie('remember_me', 'true', time() + 604800, '/');
                setcookie('user_id', $user['id'], time() + 604800, '/');
            } else {
                setcookie('remember_me', 'true', time() + 360, '/');
                setcookie('user_id', $user['id'], time() + 360, '/');
            }
            
            mysqli_close($con);
            
            if ($user['user_type'] === 'admin') {
                header('location: ../View/admin_dashboard.php');
            } else {
                header('location: ../View/dashboard.php');
            }
            exit();
        } else {
            mysqli_close($con);
            header('location: ../View/login.php?error=invalid_password&email=' . urlencode($email));
            exit();
        }
    } else {
        mysqli_close($con);
        header('location: ../View/login.php?error=invalid_user');
        exit();
    }
} else {
    mysqli_close($con);
    header('location: ../View/login.php?error=badrequest');
    exit();
}
?>
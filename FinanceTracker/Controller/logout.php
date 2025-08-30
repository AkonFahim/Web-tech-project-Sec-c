<?php
session_start();
setcookie('status', '', time() - 3600, '/'); // Delete the cookie
session_destroy(); // Destroy the session
header('location: ../View/login.php');
exit();
?>
<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
if(isset($_SESSION['user_id'])) {
    header('location: dashboard.php');
    exit();
}

include '../Model/usersModel.php';

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me']) && isset($_COOKIE['user_id'])) {
    include '../Controller/db_connection.php';
    
    $user = getActiveUserById($con, $_COOKIE['user_id']);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        
        mysqli_close($con);
        header('location: dashboard.php');
        exit();
    }
    mysqli_close($con);
}

$err1 = $err2 = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    include '../Controller/db_connection.php';
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    if (empty($email) || empty($password)) {
        $err1 = "Please fill in all fields!";
    } 
    elseif (!isValidEmail($email)) {
        $err1 = "..Please enter a valid email address!";
    }
    else {
        $user = getUserByEmail($con, $email);
        
        if ($user) {
            if ($user['status'] === 'Blocked') {
                session_unset();
                session_destroy();
                
                if (isset($_COOKIE['remember_me'])) {
                    setcookie('remember_me', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['user_id'])) {
                    setcookie('user_id', '', time() - 3600, '/');
                }
                
                mysqli_close($con);
                header('location: errors/blockeduser.php');
                exit(); 
            }
            
            elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];
                
                if ($remember) {
                    $cookie_expiry = time() + (7 * 24 * 60 * 60); // 7 days
                    setcookie('user_id', $user['id'], $cookie_expiry, "/");
                    setcookie('remember_me', 'true', $cookie_expiry, "/");
                }
                
                mysqli_close($con);
                
                header('location: dashboard.php');
                exit();
            } else {
                $err1 = "Invalid password!";
            }
        } else {
            $err1 = "No account found with this email!";
        }
    }
    
    mysqli_close($con);
}

function isValidEmail($email) {
    if (strpos($email, '@') === false) {
        return false;
    }
    
    $parts = explode('@', $email);
    $localPart = $parts[0];
    $domain = $parts[1];
    
    if (empty($localPart) || empty($domain)) {
        return false;
    }
    
    if (strpos($domain, '.') === false) {
        return false;
    }
    
    $domainParts = explode('.', $domain);
    if (count($domainParts) < 2 || empty($domainParts[0]) || empty($domainParts[1])) {
        return false;
    }
    
    if (strpos($email, ' ') !== false) {
        return false;
    }
    
    return true;
}

if(isset($_REQUEST['error'])){
    $error = $_REQUEST['error'];
    
    if($error == "invalid_user"){
        $err1 = "Please enter a valid User's email";
    } 
    elseif($error == "invalid_password") 
    {
        $err1 = "Please enter a valid password!";
    } 
    elseif($error == "empty_fields") 
    {
        $err1 = "Please fill in all fields!";
    } 
    elseif($error == "badrequest")
    {
        $err2 = "Please login first!";
    }
     elseif($error == "session_expired")
     {
        $err2 = "Your session has expired. Please login again.";
    }
     elseif($error == "database_error")
     {
        $err2 = "A system error occurred. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finance Tracker - Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../Asset/login.css">
</head>
<body>
  <div class="container">
    <div class="logo" onclick="window.location.href='landing.php'">
      <h1><i class="fas fa-wallet"></i> Finance Tracker</h1>
      <p>Manage your money smartly</p>
    </div>
    
    <div class="card-container">
      <div class="info-panel">
        <h2>Welcome Back!</h2>
        <p>We're glad to see you again. Log in to access your financial dashboard.</p></br>
        
        <ul>
          <li><i class="fas fa-chart-pie"></i> View your spending analytics</li>
          <li><i class="fas fa-bell"></i> Check upcoming bill reminders</li>
          <li><i class="fas fa-piggy-bank"></i> Track your savings progress</li>
          <li><i class="fas fa-goal-net"></i> Monitor your financial goals</li>
        </ul>
      </div>
      
      <div class="form-panel">
        <div class="form-header">
          <h2>Sign In to Your Account</h2>
          <p>Enter your credentials to continue</p>
        </div>
        
        <form method="post" action="../Controller/loginCheck.php" id="loginForm" novalidate>
          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="loginEmail" name="email" placeholder="Email Address">
          </div>
          
          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="loginPassword" name="password" placeholder="Password">
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          
          <div class="remember-forgot">
            <div class="remember">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Remember me for 7 days</label>
            </div>
            <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
          </div>
          
          <?php if(!empty($err1) || !empty($err2)): ?>
          <div id="errorMessage" class="error-message">
            <?php if(!empty($err1)): echo $err1; endif; ?>
            <?php if(!empty($err2)): echo $err2; endif; ?>
          </div>
          <?php else: ?>
          <div id="errorMessage" class="error-message" style="display: none;"></div>
          <?php endif; ?>
          
          <button type="submit" name="submit" class="btn" id="signin-btn">Sign In</button>
        </form>
        
        <div class="form-divider"><span>Or</span></div>
        
        <div class="social-login">
          <button class="social-btn">
            <i class="fab fa-google"></i> Google
          </button>
          <button class="social-btn">
            <i class="fab fa-facebook"></i> Facebook
          </button>
        </div>
        
        <div class="form-footer">
          <p>Don't have an account? <a href="register.php" class="toggle-form">Create Account</a></p>
        </div>
      </div>
    </div>
  </div>

  <script src="../Asset/login.js"></script>
</body>
</html>
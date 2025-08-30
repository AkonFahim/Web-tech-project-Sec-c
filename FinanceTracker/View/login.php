<?php
session_start();

if(isset($_REQUEST['error'])){
    $error = $_REQUEST['error'];
    
    if($error == "invalid_user"){
        $err1 = "Please enter a valid email/password!";
    } elseif($error == "empty_fields") {
        $err1 = "Please fill in all fields!";
    } elseif($error == "badrequest"){
        $err2 = "Please login first!";
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
        
        <form method="post" action="../Controller/loginCheck.php" id="loginForm">
          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="loginEmail" name="email" placeholder="Email Address" required>
          </div>
          
          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="loginPassword" name="password" placeholder="Password" required>
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          
          <div class="remember-forgot">
            <div class="remember">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Remember me</label>
            </div>
            <a href="#" class="forgot-password" id="forgotPasswordLink">Forgot Password?</a>
          </div>
          
          <div id="errorMessage" class="error-message">
            <?php if(isset($err1)){echo $err1;} ?>
            <?php if(isset($err2)){echo $err2;} ?>
          </div>
          <div id="successMessage" class="success-message"></div>
          
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

  <div class="modal" id="forgotPasswordModal">
    <div class="modal-content">
      <button class="modal-close" id="closeModal">&times;</button>
      <div class="modal-header">
        <h2>Reset Your Password</h2>
        <p>Enter your email to receive a reset link</p>
      </div>
      
      <form id="forgotPasswordForm">
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" id="resetEmail" placeholder="Email Address" required>
        </div>
        
        <div id="resetErrorMessage" class="error-message"></div>
        <div id="resetSuccessMessage" class="success-message"></div>
        
        <button type="submit" class="btn">Send Reset Link</button>
      </form>
    </div>
  </div>

  <script src="../Asset/login.js"></script>
</body>
</html>
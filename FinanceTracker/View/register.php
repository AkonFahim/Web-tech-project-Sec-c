<?php
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finance Tracker - Register</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../Asset/register.css">
</head>
<body>
  <div class="container">
    <div class="logo" onclick="window.location.href='landing.php'">
      <h1><i class="fas fa-wallet"></i> Finance Tracker</h1>
      <p>Manage your money smartly</p>
    </div>
    
    <div class="card-container">
      <div class="info-panel">
        <h2>Why Join Finance Tracker?</h2>
        <ul>
          <li><i class="fas fa-chart-pie"></i> Track expenses with visual reports</li>
          <li><i class="fas fa-dollar-sign"></i> Create customized budgets</li>
          <li><i class="fas fa-bell"></i> Never miss bill payments</li>
          <li><i class="fas fa-piggy-bank"></i> Set and achieve savings goals</li>
          <li><i class="fas fa-mobile-alt"></i> Access anywhere on mobile</li>
        </ul>
      </div>
      
      <div class="form-panel">
        <div class="form-header">
          <h2>Create Your Account</h2>
          <p>Join thousands managing their money smarter</p>
        </div>
        
        <form id="registerForm">
          <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" id="fullName" placeholder="Full Name" required>
          </div>
          
          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="Email" id="regEmail" placeholder="Email Address" required>
          </div>
          
          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" placeholder="Password" required>
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          <div id="passwordStrength" class="password-strength"></div>
          
          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
            <button type="button" class="toggle-password" id="toggleConfirmPassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          
          <div id="errorMessage" class="error-message"></div>
          <div id="successMessage" class="success-message"></div>
          
          <button type="submit" class="btn">Create Account</button>
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
          <p>Already have an account? <a href="login.php" class="toggle-form">Sign In</a></p>
        </div>
      </div>
    </div>
  </div>

  <script src="../Asset/register.js"></script>

</body>
</html>
<?php
session_start();

// Initialize error variables
$err1 = $err2 = $err3 = $err4 = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    
    // Validate fields
    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $err1 = "Please fill in all fields";
    } elseif (!isValidEmail($email)) {
        $err2 = "Please enter a valid email address";
    } elseif (strlen($password) < 6) {
        $err3 = "Password must be at least 6 characters";
    } elseif ($password !== $confirmPassword) {
        $err4 = "Passwords do not match";
    } else {
        // All validation passed - process registration
        $_SESSION['registration_success'] = "Account created successfully! Please login.";
        header('location: login.php');
        exit();
    }
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
    
    $tld = end($domainParts);
    if (strlen($tld) < 2) {
        return false;
    }
    
    return true;
}
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
        <h2>Why Join Finance Tracker?</h2></br>
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
        
        <form method="post" action="register.php" id="registerForm">
          <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" id="fullName" name="fullName" placeholder="Full Name" required value="<?php echo isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : ''; ?>">
          </div>
          
          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="regEmail" name="email" placeholder="Email Address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
          </div>
          
          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          <div id="passwordStrength" class="password-strength"></div>
          
          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
            <button type="button" class="toggle-password" id="toggleConfirmPassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          
          <div id="errorMessage" class="error-message">
            <?php 
            if (!empty($err1)) echo $err1 . '<br>';
            if (!empty($err2)) echo $err2 . '<br>';
            if (!empty($err3)) echo $err3 . '<br>';
            if (!empty($err4)) echo $err4 . '<br>';
            ?>
          </div>
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
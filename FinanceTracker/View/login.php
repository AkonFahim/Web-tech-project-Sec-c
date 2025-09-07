<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header('location: dashboard.php');
    exit();
}
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me']) && isset($_COOKIE['user_id'])) {
    include '../Controller/db_connection.php';
    $user_id = $_COOKIE['user_id'];
    $sql = "SELECT * FROM users WHERE id = '$user_id' AND status = 'active'";
    $result = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
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

if(isset($_REQUEST['error'])){
    $error = $_REQUEST['error'];
    
    if($error == "invalid_user"){
        $err1 = "Please enter a valid email";
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
            <input type="email" id="loginEmail" name="email" placeholder="Email Address" required 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : (isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''); ?>">
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
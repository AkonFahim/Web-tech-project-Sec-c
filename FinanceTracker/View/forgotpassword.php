<?php
session_start();

if(isset($_SESSION['user_id'])) {
    header('location: dashboard.php');
    exit();
}

$error_msg = $success_msg = '';

if(isset($_REQUEST['error'])){
    $error = $_REQUEST['error'];
    
    if($error == "invalid_email"){
        $error_msg = "No account found with this email address";
    } 
    else if($error == "blocked_account") 
    {
        header('location: ../View/errors/blockeduser.php');
        exit();
    }
    else if($error == "server_error")
    {
        $error_msg = "An error occurred. Please try again later.";
    }
    else if($error == "empty_email")
    {
        $error_msg = "Please enter your email address";
    }
}

if(isset($_REQUEST['success'])){
    $success = $_REQUEST['success'];
    
    if($success == "reset_sent"){
        $success_msg = "Password reset link has been sent to your email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finance Tracker - Forgot Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../Asset/login.css">
  <style>
    .forgot-password-container {
      max-width: 500px;
      margin: 0 auto;
    }
    .back-to-login {
      text-align: center;
      margin-top: 20px;
    }
    .instructions {
      background-color: #f0fdf4;
      border-left: 4px solid #22c55e;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    .instructions p {
      margin: 5px 0;
      color: #1f2937;
    }
    .redirect-message {
      text-align: center;
      margin-top: 15px;
      color: #6b7280;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo" onclick="window.location.href='landing.php'">
      <h1><i class="fas fa-wallet"></i> Finance Tracker</h1>
      <p>Manage your money smartly</p>
    </div>
    
    <div class="card-container forgot-password-container">
      <div class="form-panel">
        <div class="form-header">
          <h2>Reset Your Password</h2>
          <p>Enter your email address to reset your password</p>
        </div>

        <div class="instructions">
          <p><i class="fas fa-info-circle"></i> We'll send you instructions to reset your password</p>
        </div>
        
        <form method="post" action="../Controller/checkforgotpassword.php" id="forgotPasswordForm">
          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="resetEmail" name="email" placeholder="Enter your email address" required
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
          </div>
          
          <?php if(!empty($error_msg)) { ?>
          <div id="errorMessage" class="error-message">
            <?php echo $error_msg; ?>
          </div>
          <?php } else { ?>
          <div id="errorMessage" class="error-message" style="display: none;"></div>
          <?php } ?>
          
          <?php if(!empty($success_msg)) { ?>
          <div id="successMessage" class="success-message">
            <?php echo $success_msg; ?>
            <div class="redirect-message">
              <i class="fas fa-clock"></i> Redirecting to login page in 2 seconds...
            </div>
          </div>
          <script>
            setTimeout(function() {
              window.location.href = 'login.php';
            }, 2000);
          </script>
          <?php } else { ?>
          <div id="successMessage" class="success-message" style="display: none;"></div>
          <?php } ?>
          
          <?php if(empty($success_msg)) { ?>
          <button type="submit" class="btn" id="submit-btn">Send Reset Instructions</button>
          <?php } ?>
        </form>
        
        <?php if(empty($success_msg)) { ?>
        <div class="back-to-login">
          <p>Remember your password? <a href="login.php" class="toggle-form">Back to Login</a></p>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <script src="../Asset/forgotpassword.js"></script>
</body>
</html>
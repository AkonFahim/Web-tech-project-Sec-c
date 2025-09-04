<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Finance Tracker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../Asset/contactus.css">
</head>
<body>
  <div class="container">
    <div class="logo" onclick="window.location.href='landing.php'">
      <h1><i class="fas fa-wallet"></i> Finance Tracker</h1>
      <p>Manage your money smartly</p>
    </div>
    
    <div class="form-panel">
      <a href="landing.php" class="close-btn">&times;</a>
      
      <div class="form-header">
        <h2>Contact Us</h2>
        <p>Have questions? We'd love to hear from you</p>
      </div>
      
      <form id="contactForm">
        <div class="input-group">
          <i class="fas fa-user"></i>
          <input type="text" id="name" placeholder="Your Name" onblur="validateName()">
        </div>
        <p id="nameError" class="error-message"></p>
        
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" id="email" placeholder="Your Email" onblur="validateEmail()">
        </div>
        <p id="emailError" class="error-message"></p>
        
        <div class="input-group">
          <i class="fas fa-comment"></i>
          <textarea id="message" placeholder="Write your message here" rows="6" onblur="validateMessage()"></textarea>
        </div>
        <p id="messageError" class="error-message"></p>
        
        <div class="captcha-container">
          <div class="captcha-question">What is <span id="captcha-question"></span>?</div>
          <div class="input-group">
            <i class="fas fa-shield-alt"></i>
            <input type="number" id="captcha" placeholder="Enter your answer">
          </div>
          <p id="captchaError" class="error-message"></p>
        </div>
        
        <button type="submit" class="btn">Send Message</button>
        <p id="success" class="success-message"></p>
      </form>
    </div>
  </div>

  <script src="../Asset/contactus.js" ></script>
</body>
</html>
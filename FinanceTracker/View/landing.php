<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal Finance Tracker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../asset/landing.css">
</head>
<body>

  <header>
    <h1><i class="fas fa-wallet"></i> Finance Tracker </h1>
    <nav>
      <a href="about.php">About Us</a>
      <a href="landing.php">Home</a>
      <a href="../view/login.php" class="btn" id="landing-login-btn">Login</a>
    </nav>
  </header>

  <section class="Large">
    <div class="Large-content">
      <h2>Take Control of Your Financial Future</h2>
      <p>Track expenses, create budgets, and achieve your financial goals with our intuitive platform</p>
      <a href="../view/login.php" class="btn" id="getStarted-btn">Get Started For Free</a>
    </div>
  
  </section>

  <section class="features">
    <h2>Spend Your Money Wisely</h2>
    <div class="features-container">
      <div class="feature-box">
        <div class="feature-icon">
          <i class="fas fa-chart-pie"></i>
        </div>
        <h3>Track Expenses</h3>
        <p>See where your money goes with intuitive spending categories and visual reports.</p>
      </div>
      
      <div class="feature-box">
        <div class="feature-icon">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <h3>Budget Planning</h3>
        <p>Create customized budgets that adapt to your lifestyle and financial goals.</p>
      </div>
      
      <div class="feature-box">
        <div class="feature-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <h3>Financial Analytics</h3>
        <p>Understand your spending patterns with detailed reports and insights.</p>
      </div>
      
      <div class="feature-box">
        <div class="feature-icon">
          <i class="fas fa-bell"></i>
        </div>
        <h3>Bill Reminders</h3>
        <p>Never miss a payment with smart notifications and scheduling.</p>
      </div>
      
      <div class="feature-box">
        <div class="feature-icon">
          <i class="fas fa-piggy-bank"></i>
        </div>
        <h3>Savings Goals</h3>
        <p>Set and track progress toward your financial dreams and aspirations.</p>
      </div>
      
      <div class="feature-box">
        <div class="feature-icon">
          <i class="fas fa-mobile-alt"></i>
        </div>
        <h3>Mobile Access</h3>
        <p>Manage your finances on the go with our iOS and Android apps.</p>
      </div>
    </div>
  </section>


  <!-- register Section -->
  <section class="register">
    <h2>Ready to Transform Your Financial Life?</h2>
    <p>Join thousands of users who have taken control of their finances with Finance Tracker </p>
    <a href="../view/register.php" class="btn" id="landing-register-btn">Create Your Account</a>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <h3><i class="fas fa-wallet"></i> Finance Tracker </h3>
      <div class="footer-links">
        <a href="contactus.php">Contact</a>
        <a href="errors/404.php">Support</a>
      </div>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
      </div>
      <div class="copyright">
        <p>© AIUB | WEB TECHNOLOGIES | SECTION C | GROUP-7.</br> All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="../asset/landing.js"></script>
  

</body>
</html>
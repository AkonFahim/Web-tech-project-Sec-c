<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal Finance Tracker</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f9f9f9;
      color: #333;
    }

    /* Navbar */
    header {
      background: #fff;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    header h1 {
      color: green;
      font-size: 22px;
    }
    nav a {
      margin-left: 20px;
      text-decoration: none;
      color: #333;
    }
    nav a:hover {
      color: green;
    }
    .btn {
      background: green;
      color: #fff;
      padding: 8px 16px;
      border-radius: 4px;
      text-decoration: none;
    }

    /* Hero Section */
    .hero {
      text-align: center;
      padding: 60px 20px;
      background: #eef6f0;
    }
    .hero h2 {
      font-size: 32px;
      margin-bottom: 15px;
    }
    .hero p {
      margin-bottom: 20px;
      font-size: 18px;
      color: #555;
    }

    /* Features Section */
    .features {
      padding: 40px 20px;
      text-align: center;
    }
    .features h3 {
      margin-bottom: 15px;
      color: green;
    }
    .feature-box {
      background: #fff;
      padding: 20px;
      margin: 15px auto;
      max-width: 400px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    /* Footer */
    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 15px;
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header>
    <h1>💲 DecaPay</h1>
    <nav>
      <a href="#">Home</a>
      <a href="#">How It Works</a>
      <a href="#">Contact</a>
      <a href="login.php" class="btn">login</a>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h2>Effectively manage your budget</h2>
    <p>The platform helps you plan and track your spending easily.</p>
    <a href="#" class="btn">Get Started</a>
  </section>

  <!-- Features Section -->
  <section class="features">
    <h2>Spend your money wisely</h2>

    <div class="feature-box">
      <h3>📊 Track Expenses</h3>
      <p>See where your money goes every day.</p>
    </div>

    <div class="feature-box">
      <h3>💰 Budget Planning</h3>
      <p>Create a budget that fits your lifestyle.</p>
    </div>

    <div class="feature-box">
      <h3>📈 Analytics</h3>
      <p>Understand your spending with reports.</p>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>© 2025 DecaPay. All rights reserved.</p>
  </footer>

</body>
</html>

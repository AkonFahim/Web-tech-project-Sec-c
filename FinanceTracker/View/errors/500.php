<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 Server Error</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #fff5f5;
      text-align: center;
      color: #333;
    }
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    h1 {
      font-size: 100px;
      margin: 0;
      color: #ff4d4d;
    }
    h2 {
      font-size: 40px;
      margin: 10px 0;
      color: #cc0000;
    }
    p {
      font-size: 18px;
      color: #666;
      margin-bottom: 30px;
    }
    button {
      background: #ff4d4d;
      color: #fff;
      padding: 12px 24px;
      border: none;
      border-radius: 25px;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background: #cc0000;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>500</h1>
    <h2>Server Error</h2>
    <p>Something went wrong on our end. Please try again later.</p>
    <button onclick="goBack()">Go Back</button>
  </div>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>
</body>
</html>

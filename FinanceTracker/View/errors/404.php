<?php

?>

<!DOCTYPE html>
<html lang="en">
<head><script type="text/javascript" src="/___vscode_livepreview_injected_script"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 Page Not Found</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #f7f9ff;
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
      color: #6e63ff;
    }
    h2 {
      font-size: 40px;
      margin: 10px 0;
      color: #4f46e5;
    }
    p {
      font-size: 18px;
      color: #8ea2c8;
      margin-bottom: 30px;
    }
    button {
      background: #f45bbf;
      color: #fff;
      padding: 12px 24px;
      border: none;
      border-radius: 25px;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background: #ff7cc4;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>404</h1>
    <h2>Oops!</h2>
    <p>The page you are looking for can’t be found.</p>
    <button onclick="goBack()">Go Back</button>
  </div>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>
</body>
</html>
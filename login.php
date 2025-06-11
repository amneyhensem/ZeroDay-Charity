<?php
session_start();

// Hardcoded admin credentials (for demo purposes)
$admin_user = 'admin';
$admin_pass = 'admin123'or'admin1234';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }
    .login-container {
      background: rgba(255,255,255,0.1);
      padding: 2rem 3rem;
      border-radius: 1rem;
      box-shadow: 0 8px 32px rgba(238,9,121,0.15), 0 1.5px 8px rgba(255,106,0,0.08);
      width: 320px;
      text-align: center;
      animation: fadeIn 1s ease forwards;
    }
    h2 {
      margin-bottom: 1.5rem;
      font-weight: 700;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px rgba(255,106,0,0.3);
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
      border: none;
      border-radius: 0.5rem;
      font-size: 1rem;
      outline: none;
    }
    button {
      width: 100%;
      padding: 0.75rem 1rem;
      background: #ee0979;
      border: none;
      border-radius: 0.5rem;
      color: #fff;
      font-size: 1.1rem;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background: #ff6a00;
    }
    .error {
      color: #ffb3b3;
      margin-bottom: 1rem;
      font-weight: 600;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="login-container" role="main" aria-labelledby="loginTitle">
    <h2 id="loginTitle">Admin Login</h2>
    <?php if ($error): ?>
      <div class="error" role="alert"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="POST" action="login.php" aria-describedby="loginDesc">
      <label for="username" class="sr-only">Username</label>
      <input type="text" id="username" name="username" placeholder="Username" required autofocus />
      <label for="password" class="sr-only">Password</label>
      <input type="password" id="password" name="password" placeholder="Password" required />
      <button type="submit" aria-label="Log in to admin panel">Login</button>
    </form>
  </div>
</body>
</html>

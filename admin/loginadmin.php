<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin</title>
  <link rel="stylesheet" href="css/style_log.css">
  <link rel="shortcut icon" href="images/kalteng.png">
</head>
<body>
  <h1>Fitru baru</h1>
  <div class="login-container">
    <div class="login-card">
      <img src="images/kalteng.png" alt="Logo" class="login-logo">
      <h2 class="login-title">Login Admin</h2>
      <form action="login_ad.php" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>

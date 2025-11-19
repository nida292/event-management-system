<?php
session_start();
require_once '../../controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController($conn);
    if ($auth->login($_POST['username'], $_POST['password'])) {
        header("Location: ../../index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
 
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

  <header class="hero">
    <h1> Campus Event Management</h1>
    <p class="tagline">Login to explore events, enroll, and connect</p>
  </header>


  <form method="POST" class="auth-form">
    <h2>Login</h2>

    <?php if (isset($error)): ?>
      <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>

    <div class="btn-container">
      <button type="submit">Login</button>
    </div>
  </form>

  <div class="auth-footer">
    <p>New here? <a href="register.php">Create an account</a></p>
  </div>
</body>
</html>

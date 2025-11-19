<?php
session_start();
require_once '../../controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController($conn);
    if ($auth->register($_POST['username'], $_POST['password'], $_POST['role'])) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
  
  <header class="hero">
    <h1>Campus Event Management</h1>
    <p class="tagline">Join us and be part of the community</p>
  </header>

  <form method="POST">
    </div class="card">
    <?php if (isset($error)): ?>
      <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Choose a Username" required>
    <input type="password" name="password" placeholder="Create a Password" required>
    <select name="role" required>
      <option value="">Select Role</option>
      <option value="student">Student</option>
      <option value="admin">Admin</option>
    </select>
    <div class="btn-container">
  <button type="submit">Register</button>
  </div>
  </form>
  </div>

  <nav>
    <a href="login.php">Already have an account? Login</a>
  </nav>
</body>
</html>

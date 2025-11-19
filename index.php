<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: views/auth/login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Campus Events Home</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <header class="hero">
    <h1> Campus Event Management</h1>
    <p class="tagline">Discover • Enroll • Connect • Celebrate</p>
  </header>

  <nav>
    <a href="index.php">Home</a>
    <?php if ($user['role'] === 'admin'): ?>
      <a href="views/events/index.php">Manage Events</a>
      <a href="views/feedback/index.php">Feedback</a>
    <?php else: ?>
      <a href="views/events/list.php">Events</a>
      <a href="views/registration/index.php">Enroll</a>
      <a href="views/feedback/index.php">Feedback</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
  </nav>

  <div class="card">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p>Your role: <strong><?php echo htmlspecialchars($user['role']); ?></strong></p>
    <?php if ($user['role'] === 'admin'): ?>
      <p>You can manage events and review feedback from students.</p>
    <?php else: ?>
      <p>You can browse events, enroll, and share feedback.</p>
    <?php endif; ?>
  </div>
</body>
</html>

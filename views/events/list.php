<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['user']['role'] !== 'student') {
    header("Location: ../../index.php");
    exit;
}

require_once '../../controllers/EventController.php';
$controller = new EventController($conn);
$events = $controller->index();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Available Events</title>
  <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
  <h2>Available Events</h2>
  <nav>
    <a href="../../index.php">Home</a>
    <a href="../registration/index.php">Enroll</a>
    <a href="../../logout.php">Logout</a>
  </nav>

  <ul>
    <?php while ($row = $events->fetch_assoc()): ?>
      <li>
        <strong><?php echo htmlspecialchars($row['event_name']); ?></strong>
        (<?php echo htmlspecialchars($row['event_date']); ?>)<br>
        <?php echo htmlspecialchars($row['description']); ?>
      </li>
    <?php endwhile; ?>
  </ul>
</body>
</html>

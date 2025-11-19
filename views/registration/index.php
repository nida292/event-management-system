<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../../config/db.php';
require_once '../../controllers/EventController.php';

$controller = new EventController($conn);
$events = $controller->index();

$user = $_SESSION['user'];


$isAdmin = ($user['role'] === 'admin');


if ($isAdmin) {
    $sql = "SELECT r.id, u.username AS student_name, u.email, e.event_name, r.enrolled_at
            FROM registration r
            JOIN users u ON r.user_id = u.id
            JOIN events e ON r.event_id = e.id
            ORDER BY r.enrolled_at DESC";
    $registrations = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $isAdmin ? 'Enrolled Students' : 'Enroll in Events'; ?></title>
  <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

  <h2 style="text-align:center;">
    <?php echo $isAdmin ? 'Enrolled Students in Events' : 'Available Events to Enroll'; ?>
  </h2>

  <nav>
    <a href="../../index.php">Home</a>
    <?php if (!$isAdmin): ?>
      <a href="../registration/index.php">Enroll</a>
    <?php endif; ?>
    <a href="../../logout.php">Logout</a>
  </nav>

  <?php if ($isAdmin): ?>
    <!-- 🧾 Admin view: List of enrolled students -->
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Student Name</th>
          <th>Email</th>
          <th>Event Name</th>
          <th>Enrolled On</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($registrations->num_rows > 0): ?>
          <?php $i = 1; while ($row = $registrations->fetch_assoc()): ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($row['student_name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['event_name']); ?></td>
              <td><?php echo htmlspecialchars($row['enrolled_at']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" style="text-align:center;">No students enrolled yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

  <?php else: ?>
    
    <ul>
      <?php while ($row = $events->fetch_assoc()): ?>
        <li>
          <strong><?php echo htmlspecialchars($row['event_name']); ?></strong>
          (<?php echo htmlspecialchars($row['event_date']); ?>)<br>
          <?php echo htmlspecialchars($row['description']); ?><br><br>

          <form method="POST" action="enroll.php">
            <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
            <button type="submit" class="btn">Enroll</button>
          </form>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php endif; ?>

</body>
</html>

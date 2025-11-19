<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$events = $conn->query("SELECT id, event_name FROM events ORDER BY event_name ASC");

$selected_event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$enrolled_students = [];

if ($selected_event_id > 0) {
    $query = "
        SELECT u.username, u.id AS user_id, e.event_name, r.enrolled_at
        FROM registrations r
        INNER JOIN users u ON r.user_id = u.id
        INNER JOIN events e ON r.event_id = e.id
        WHERE r.event_id = $selected_event_id
        ORDER BY u.username ASC
    ";
    $enrolled_students = $conn->query($query);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Enrollments</title>
  <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
  <header class="hero">
    <h1>Manage Enrollments</h1>
    <p class="tagline">View which students enrolled in each event</p>
  </header>

  <nav>
    <a href="../../index.php">Home</a>
    <a href="../events/index.php">Manage Events</a>
    <a href="../feedback/list.php">Feedback</a>
    <a href="../../logout.php">Logout</a>
  </nav>

  <form method="GET" class="feedback-form">
    <h2>Select Event</h2>
    <select name="event_id" onchange="this.form.submit()" required>
      <option value="">-- Choose an Event --</option>
      <?php while ($event = $events->fetch_assoc()): ?>
        <option value="<?php echo $event['id']; ?>" 
          <?php echo ($selected_event_id == $event['id']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($event['event_name']); ?>
        </option>
      <?php endwhile; ?>
    </select>
  </form>

  <?php if ($selected_event_id > 0): ?>
    <div class="card">
      <h2>Students Enrolled in "<?php echo htmlspecialchars($conn->query("SELECT event_name FROM events WHERE id=$selected_event_id")->fetch_assoc()['event_name']); ?>"</h2>

      <?php if ($enrolled_students && $enrolled_students->num_rows > 0): ?>
        <table class="styled-table">
          <thead>
            <tr>
              <th>Student ID</th>
              <th>Student Name</th>
              <th>Enrolled On</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $enrolled_students->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['enrolled_at']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align:center;">No students have enrolled in this event yet.</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</body>
</html>

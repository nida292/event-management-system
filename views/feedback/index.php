<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

$events = $conn->query("
    SELECT e.id, e.event_name 
    FROM events e
    INNER JOIN registrations r ON e.id = r.event_id
    WHERE r.user_id = $user_id
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $comments = trim($_POST['comments']);

    if (!empty($comments)) {
        $sql = "INSERT INTO feedback (user_id, event_id, comments) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("iis", $user_id, $event_id, $comments);
        if ($stmt->execute()) {
            $success = "Thank you! Your feedback has been submitted.";
        } else {
            $error = "Error submitting feedback: " . $stmt->error;
        }
    } else {
        $error = "Please write your feedback before submitting.";
    }
}

$feedbacks = $conn->query("
    SELECT f.comments, f.created_at, e.event_name, u.username
    FROM feedback f
    INNER JOIN users u ON f.user_id = u.id
    INNER JOIN events e ON f.event_id = e.id
    ORDER BY f.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Feedback</title>
  <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
  <header class="hero">
    <h1> Campus Event Feedback</h1>
    <p class="tagline">Share your experience and read what others think</p>
  </header>

  <nav>
    <a href="../../index.php">Home</a>
    <a href="../events/list.php">Events</a>
    <a href="../registration/index.php">Enroll</a>
    <a href="../../logout.php">Logout</a>
  </nav>

  <form method="POST" class="feedback-form">
    <h2>Submit Feedback</h2>

    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <label for="event_id">Select Event:</label>
    <select name="event_id" id="event_id" required>
      <option value="">-- Choose an Event --</option>
      <?php while ($event = $events->fetch_assoc()): ?>
        <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['event_name']); ?></option>
      <?php endwhile; ?>
    </select>

    <textarea name="comments" rows="5" placeholder="Write your feedback..." required></textarea>

    <div class="btn-container">
      <button type="submit">Submit Feedback</button>
    </div>
  </form>

 
  <section class="feedback-board">
    <h2> All Students' Feedback</h2>

    <?php if ($feedbacks->num_rows > 0): ?>
      <ul class="feedback-list">
        <?php while($fb = $feedbacks->fetch_assoc()): ?>
          <li>
            <div class="feedback-event">
              <strong><?php echo htmlspecialchars($fb['event_name']); ?></strong>
            </div>
            <p><?php echo htmlspecialchars($fb['comments']); ?></p>
            <small>By <?php echo htmlspecialchars($fb['username']); ?> • <?php echo date("M d, Y h:i A", strtotime($fb['created_at'])); ?></small>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p class="no-feedback">No feedback has been submitted yet.</p>
    <?php endif; ?>
  </section>
</body>
</html>

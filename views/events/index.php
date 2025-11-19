<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

require_once '../../controllers/EventController.php';
$controller = new EventController($conn);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $controller->store($_POST['event_name'], $_POST['event_date'], $_POST['description']);
        header("Location: index.php?success=added");
        exit;
    } elseif (isset($_POST['update'])) {
        $controller->update((int)$_POST['id'], $_POST['event_name'], $_POST['event_date'], $_POST['description']);
        header("Location: index.php?success=updated");
        exit;
    } elseif (isset($_POST['delete'])) {
        $controller->destroy((int)$_POST['id']);
        header("Location: index.php?success=deleted");
        exit;
    }
}

$events = $controller->index();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Events</title>
  <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
  <h2>Manage Events</h2>
  <nav>
    <a href="../../index.php">Home</a>
    <a href="../registration/index.php">| Enroll |</a>
    <a href="../../logout.php">Logout</a>
  </nav>

  <?php if (isset($_GET['success'])): ?>
    <p style="color: green; font-weight: bold;">
      <?php
        if ($_GET['success'] === 'added') echo "Event added successfully!";
        elseif ($_GET['success'] === 'updated') echo "Event updated successfully!";
        elseif ($_GET['success'] === 'deleted') echo "Event deleted successfully!";
      ?>
    </p>
  <?php endif; ?>

  
  <div class="card">
    <h3>Add Event</h3>
    <form method="POST">
      <input type="text" name="event_name" placeholder="Event Name" required>
      <input type="date" name="event_date" required>
      <textarea name="description" placeholder="Description"></textarea>
      <br><button type="submit" name="add">Add Event</button></br>
    </form>
  </div>


<?php
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
?>
<div class="card">
    <h3>Existing Events</h3>
<?php while ($row = $events->fetch_assoc()): ?>
   
  <div class="card">
      
    <?php if ($editId === (int)$row['id']): ?>
      <form method="POST" class="edit-form">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="text" name="event_name" value="<?php echo htmlspecialchars($row['event_name']); ?>" required>
        <input type="date" name="event_date" value="<?php echo htmlspecialchars($row['event_date']); ?>" required>
        <textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea>
        <div class="btn-container">
          <button type="submit" name="update" class="update-btn">Save Changes</button>
          <a href="index.php" class="cancel-btn">Cancel</a>
        </div>
      </form>
    <?php else: ?>
      <div class="card-header"><?php echo htmlspecialchars($row['event_name']); ?></div>
      <div class="card-sub"><?php echo htmlspecialchars($row['event_date']); ?></div>
      <p><?php echo htmlspecialchars($row['description']); ?></p>
  <div class="btn-container">
  <a href="index.php?edit=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>

  <button form="deleteForm<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');" type="submit" name="delete" class="delete-btn">
    Delete
  </button>
</div>

<form id="deleteForm<?php echo $row['id']; ?>" method="POST" style="display: none;">
  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
</form>

    <?php endif; ?>
  </div>
<?php endwhile; ?>
</div>

<?php
session_start();
require_once '../../config/db.php'; 

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$event_id = $_POST['event_id'];

$check = $conn->query("SELECT * FROM registrations WHERE user_id=$user_id AND event_id=$event_id");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO registrations (user_id, event_id) VALUES ($user_id, $event_id)");
}

header("Location: index.php");
exit;
?>

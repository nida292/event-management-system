<?php
class Feedback {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function submit($userId, $message) {
        $stmt = $this->conn->prepare("INSERT INTO feedback (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $message);
        return $stmt->execute();
    }

    public function listAll() {
        return $this->conn->query("
            SELECT f.id, f.message, f.created_at, u.username
            FROM feedback f
            JOIN users u ON f.user_id = u.id
            ORDER BY f.created_at DESC
        ");
    }
}
?>

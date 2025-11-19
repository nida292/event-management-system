<?php
class Registration {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function enroll($userId, $eventId) {
        $stmt = $this->conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $eventId);
        return $stmt->execute();
    }

    public function userEnrollments($userId) {
        $stmt = $this->conn->prepare("
            SELECT r.id, e.event_name, e.event_date
            FROM registrations r
            JOIN events e ON r.event_id = e.id
            WHERE r.user_id = ?
            ORDER BY e.event_date ASC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function isEnrolled($userId, $eventId) {
        $stmt = $this->conn->prepare("SELECT id FROM registrations WHERE user_id=? AND event_id=?");
        $stmt->bind_param("ii", $userId, $eventId);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public function cancel($userId, $eventId) {
        $stmt = $this->conn->prepare("DELETE FROM registrations WHERE user_id=? AND event_id=?");
        $stmt->bind_param("ii", $userId, $eventId);
        return $stmt->execute();
    }
}
?>

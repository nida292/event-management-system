<?php
class Event {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function getAll() {
        return $this->conn->query("SELECT * FROM events ORDER BY event_date ASC");
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM events WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function add($name, $date, $desc) {
        $stmt = $this->conn->prepare("INSERT INTO events (event_name, event_date, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $date, $desc);
        return $stmt->execute();
    }

    public function update($id, $name, $date, $desc) {
        $stmt = $this->conn->prepare("UPDATE events SET event_name=?, event_date=?, description=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $date, $desc, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM events WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

<?php
class User {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function create($username, $password, $role) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hash, $role);
        return $stmt->execute();
    }

    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>

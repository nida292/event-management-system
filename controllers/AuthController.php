<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;
    public function __construct($conn) { $this->user = new User($conn); }

    public function register($username, $password, $role) {
        return $this->user->create($username, $password, $role);
    }

    public function login($username, $password) {
        $found = $this->user->findByUsername($username);
        if ($found && password_verify($password, $found['password'])) {
            $_SESSION['user'] = [
                'id' => $found['id'],
                'username' => $found['username'],
                'role' => $found['role']
            ];
            return true;
        }
        return false;
    }
}
?>

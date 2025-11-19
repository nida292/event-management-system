<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Registration.php';

class RegistrationController {
    private $conn;
    private $reg;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllRegistrations() {
        $sql = "SELECT * FROM registrations";
        return $this->conn->query($sql);
    }


    
    
    public function enroll($userId, $eventId) { return $this->reg->enroll($userId, $eventId); }
    public function cancel($userId, $eventId) { return $this->reg->cancel($userId, $eventId); }
    public function userEnrollments($userId) { return $this->reg->userEnrollments($userId); }
    public function isEnrolled($userId, $eventId) { return $this->reg->isEnrolled($userId, $eventId); }
    


}
?>

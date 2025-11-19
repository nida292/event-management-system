<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Feedback.php';

class FeedbackController {
    private $fb;
    public function __construct($conn) { $this->fb = new Feedback($conn); }

    public function submit($userId, $message) { return $this->fb->submit($userId, $message); }
    public function listAll() { return $this->fb->listAll(); }
}
?>

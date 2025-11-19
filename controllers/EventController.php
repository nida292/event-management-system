<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Event.php';

class EventController {
    private $event;
    public function __construct($conn) { $this->event = new Event($conn); }

    public function index() { return $this->event->getAll(); }
    public function show($id) { return $this->event->getById($id); }
    public function store($name, $date, $desc) { return $this->event->add($name, $date, $desc); }
    public function update($id, $name, $date, $desc) { return $this->event->update($id, $name, $date, $desc); }
    public function destroy($id) { return $this->event->delete($id); }
    
}
?>

<?php
class Person {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    protected function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    protected function validateDate($date) {
        return (bool)strtotime($date);
    }
}
?>

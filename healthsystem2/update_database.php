<?php
require_once 'config/database.php';

try {
    // Update the users table to include new roles
    $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'doctor', 'patient', 'visitor', 'guardian') DEFAULT 'staff'";
    $pdo->exec($sql);
    echo "Database updated successfully! Now you can select Doctor, Visitor, and Guardian roles.";
    echo "<br><br><a href='modules/users.php'>Go to User Management</a>";
} catch(PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>

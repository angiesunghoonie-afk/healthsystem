<?php
require_once 'config/database.php';

try {
    // Create stock_movements table
    $sql = "CREATE TABLE IF NOT EXISTS stock_movements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        medicine_id INT NOT NULL,
        movement_type ENUM('in', 'out') NOT NULL,
        quantity INT NOT NULL,
        reason TEXT,
        user_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (medicine_id) REFERENCES medicine_inventory(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    $pdo->exec($sql);
    echo "Stock movements table created successfully!";
    echo "<br><br><a href='modules/medicine_inventory.php'>Go to Medicine Inventory</a>";

} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>

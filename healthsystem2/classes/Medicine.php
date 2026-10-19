<?php
require_once 'person.php';

class Medicine extends Person {
    
    public function addMedicine($data) {
        $sql = "INSERT INTO medicine_inventory (medicine_name, generic_name, category, quantity, unit, expiry_date, manufacturer, supplier, price) 
                VALUES (:medicine_name, :generic_name, :category, :quantity, :unit, :expiry_date, :manufacturer, :supplier, :price)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':medicine_name' => $this->sanitize($data['medicine_name']),
                ':generic_name' => $this->sanitize($data['generic_name']),
                ':category' => $this->sanitize($data['category']),
                ':quantity' => $data['quantity'],
                ':unit' => $this->sanitize($data['unit']),
                ':expiry_date' => $data['expiry_date'],
                ':manufacturer' => $this->sanitize($data['manufacturer']),
                ':supplier' => $this->sanitize($data['supplier']),
                ':price' => $data['price']
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function getAllMedicines() {
        $sql = "SELECT * FROM medicine_inventory ORDER BY medicine_name ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getMedicineById($id) {
        $sql = "SELECT * FROM medicine_inventory WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function searchMedicines($keyword) {
        $sql = "SELECT * FROM medicine_inventory 
                WHERE medicine_name LIKE :keyword 
                OR generic_name LIKE :keyword 
                OR category LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }
    
    public function updateMedicine($id, $data) {
        $sql = "UPDATE medicine_inventory SET 
                medicine_name = :medicine_name,
                generic_name = :generic_name,
                category = :category,
                quantity = :quantity,
                unit = :unit,
                expiry_date = :expiry_date,
                manufacturer = :manufacturer,
                supplier = :supplier,
                price = :price
                WHERE id = :id";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $data[':id'] = $id;
            return $stmt->execute($data);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function deleteMedicine($id) {
        $sql = "DELETE FROM medicine_inventory WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function updateQuantity($id, $quantity) {
        $sql = "UPDATE medicine_inventory SET quantity = :quantity WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':quantity' => $quantity, ':id' => $id]);
    }
    
    public function getLowStockMedicines($threshold = 10) {
        $sql = "SELECT * FROM medicine_inventory WHERE quantity <= :threshold ORDER BY quantity ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':threshold' => $threshold]);
        return $stmt->fetchAll();
    }
    
    public function getExpiringMedicines($days = 30) {
        $sql = "SELECT * FROM medicine_inventory 
                WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL :days DAY) 
                AND expiry_date >= CURDATE()
                ORDER BY expiry_date ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':days' => $days]);
        return $stmt->fetchAll();
    }
    
    public function getMedicineCount() {
        $sql = "SELECT COUNT(*) as count FROM medicine_inventory";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
?>

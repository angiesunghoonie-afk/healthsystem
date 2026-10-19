<?php
require_once 'person.php';

class Patient extends Person {
    
    public function addPatient($data) {
        $sql = "INSERT INTO patients (first_name, last_name, middle_name, birth_date, gender, address, contact_number, emergency_contact, emergency_contact_number) 
                VALUES (:first_name, :last_name, :middle_name, :birth_date, :gender, :address, :contact_number, :emergency_contact, :emergency_contact_number)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':first_name' => $this->sanitize($data['first_name']),
                ':last_name' => $this->sanitize($data['last_name']),
                ':middle_name' => $this->sanitize($data['middle_name']),
                ':birth_date' => $data['birth_date'],
                ':gender' => $this->sanitize($data['gender']),
                ':address' => $this->sanitize($data['address']),
                ':contact_number' => $this->sanitize($data['contact_number']),
                ':emergency_contact' => $this->sanitize($data['emergency_contact']),
                ':emergency_contact_number' => $this->sanitize($data['emergency_contact_number'])
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function getAllPatients() {
        $sql = "SELECT * FROM patients ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getPatientById($id) {
        $sql = "SELECT * FROM patients WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function searchPatients($keyword) {
        $sql = "SELECT * FROM patients
                WHERE CONCAT(first_name, ' ', last_name) LIKE :keyword
                OR first_name LIKE :keyword
                OR last_name LIKE :keyword
                OR middle_name LIKE :keyword
                OR contact_number LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }
    
    public function updatePatient($id, $data) {
        $sql = "UPDATE patients SET 
                first_name = :first_name,
                last_name = :last_name,
                middle_name = :middle_name,
                birth_date = :birth_date,
                gender = :gender,
                address = :address,
                contact_number = :contact_number,
                emergency_contact = :emergency_contact,
                emergency_contact_number = :emergency_contact_number
                WHERE id = :id";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $data[':id'] = $id;
            return $stmt->execute($data);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function deletePatient($id) {
        $sql = "DELETE FROM patients WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getPatientCount() {
        $sql = "SELECT COUNT(*) as count FROM patients";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
?>

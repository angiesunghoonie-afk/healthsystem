<?php
require_once 'person.php';

class Consultation extends Person {
    
    public function addConsultation($data) {
        $sql = "INSERT INTO consultations (patient_id, consultation_date, chief_complaint, diagnosis, treatment, notes, attending_physician) 
                VALUES (:patient_id, :consultation_date, :chief_complaint, :diagnosis, :treatment, :notes, :attending_physician)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':patient_id' => $data['patient_id'],
                ':consultation_date' => $data['consultation_date'],
                ':chief_complaint' => $this->sanitize($data['chief_complaint']),
                ':diagnosis' => $this->sanitize($data['diagnosis']),
                ':treatment' => $this->sanitize($data['treatment']),
                ':notes' => $this->sanitize($data['notes']),
                ':attending_physician' => $this->sanitize($data['attending_physician'])
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function getAllConsultations() {
        $sql = "SELECT c.*, p.first_name, p.last_name 
                FROM consultations c 
                JOIN patients p ON c.patient_id = p.id 
                ORDER BY c.consultation_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getConsultationById($id) {
        $sql = "SELECT c.*, p.first_name, p.last_name 
                FROM consultations c 
                JOIN patients p ON c.patient_id = p.id 
                WHERE c.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getConsultationsByPatient($patient_id) {
        $sql = "SELECT * FROM consultations WHERE patient_id = :patient_id ORDER BY consultation_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':patient_id' => $patient_id]);
        return $stmt->fetchAll();
    }
    
    public function updateConsultation($id, $data) {
        $sql = "UPDATE consultations SET 
                patient_id = :patient_id,
                consultation_date = :consultation_date,
                chief_complaint = :chief_complaint,
                diagnosis = :diagnosis,
                treatment = :treatment,
                notes = :notes,
                attending_physician = :attending_physician
                WHERE id = :id";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $data[':id'] = $id;
            return $stmt->execute($data);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function deleteConsultation($id) {
        $sql = "DELETE FROM consultations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getConsultationCount() {
        $sql = "SELECT COUNT(*) as count FROM consultations";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public function getRecentConsultations($limit = 5) {
        $sql = "SELECT c.*, p.first_name, p.last_name 
                FROM consultations c 
                JOIN patients p ON c.patient_id = p.id 
                ORDER BY c.consultation_date DESC 
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../config/database.php';
require_once '../classes/MedicalRecord.php';
require_once '../classes/Patient.php';

$medicalRecord = new MedicalRecord($pdo);
$patient = new Patient($pdo);
$patients = $patient->getAllPatients();
$records = $medicalRecord->getAllMedicalRecords();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $data = [
            'patient_id' => $_POST['patient_id'],
            'record_date' => $_POST['record_date'],
            'vital_signs' => $_POST['vital_signs'],
            'allergies' => $_POST['allergies'],
            'chronic_conditions' => $_POST['chronic_conditions'],
            'surgeries' => $_POST['surgeries'],
            'family_history' => $_POST['family_history'],
            'notes' => $_POST['notes']
        ];
        
        if ($medicalRecord->addMedicalRecord($data)) {
            header('Location: medical_records.php?success=added');
            exit();
        }
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        if ($medicalRecord->deleteMedicalRecord($id)) {
            header('Location: medical_records.php?success=deleted');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../components/header.php'; ?>
</head>
<body>
    <?php include '../components/navbar2.php'; ?>
    
    <div class="dashboard-container">
        <?php include '../components/sidebar.php'; ?>
        
        <main class="dashboard-content">
            <div class="module-header">
                <h1>Medical Records</h1>
            </div>

            <div class="module-content">
                <div class="form-section">
                    <h2>Add Medical Record</h2>
                    <form method="POST" class="module-form">
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label>Patient</label>
                            <select name="patient_id" required>
                                <option value="">Select Patient</option>
                                <?php foreach ($patients as $p): ?>
                                    <option value="<?php echo $p['id']; ?>">
                                        <?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Record Date</label>
                            <input type="date" name="record_date" required>
                        </div>
                        <div class="form-group">
                            <label>Vital Signs</label>
                            <textarea name="vital_signs" rows="3" placeholder="BP, Temperature, Pulse, Respiratory Rate"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Allergies</label>
                            <textarea name="allergies" rows="2" placeholder="Enter known allergies"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Chronic Conditions</label>
                            <textarea name="chronic_conditions" rows="2" placeholder="Enter chronic conditions"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Previous Surgeries</label>
                            <textarea name="surgeries" rows="2" placeholder="Enter previous surgeries"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Family History</label>
                            <textarea name="family_history" rows="2" placeholder="Enter family medical history"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" rows="3" placeholder="Additional notes"></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Add Record</button>
                    </form>
                </div>

                <div class="table-section">
                    <h2>Medical Records List</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Vital Signs</th>
                                <th>Allergies</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($records)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">No records found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($records as $r): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($r['record_date']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($r['vital_signs'], 0, 50) . '...'); ?></td>
                                        <td><?php echo htmlspecialchars(substr($r['allergies'], 0, 30) . '...'); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <?php include '../components/footer.php'; ?>
</body>
</html>

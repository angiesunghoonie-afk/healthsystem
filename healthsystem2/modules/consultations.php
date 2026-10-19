<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../config/database.php';
require_once '../classes/Consultation.php';
require_once '../classes/Patient.php';

$consultation = new Consultation($pdo);
$patient = new Patient($pdo);
$patients = $patient->getAllPatients();
$consultations = $consultation->getAllConsultations();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $data = [
            'patient_id' => $_POST['patient_id'],
            'consultation_date' => $_POST['consultation_date'],
            'chief_complaint' => $_POST['chief_complaint'],
            'diagnosis' => $_POST['diagnosis'],
            'treatment' => $_POST['treatment'],
            'notes' => $_POST['notes'],
            'attending_physician' => $_POST['attending_physician']
        ];
        
        if ($consultation->addConsultation($data)) {
            header('Location: consultations.php?success=added');
            exit();
        }
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        if ($consultation->deleteConsultation($id)) {
            header('Location: consultations.php?success=deleted');
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
                <h1>Consultations</h1>
            </div>

            <div class="module-content">
                <div class="form-section">
                    <h2>New Consultation</h2>
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
                            <label>Consultation Date</label>
                            <input type="date" name="consultation_date" required>
                        </div>
                        <div class="form-group">
                            <label>Chief Complaint</label>
                            <textarea name="chief_complaint" rows="3" required placeholder="Enter chief complaint"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Diagnosis</label>
                            <textarea name="diagnosis" rows="3" placeholder="Enter diagnosis"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Treatment</label>
                            <textarea name="treatment" rows="3" placeholder="Enter treatment plan"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Attending Physician</label>
                            <input type="text" name="attending_physician" required placeholder="Enter physician name">
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" rows="3" placeholder="Additional notes"></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Add Consultation</button>
                    </form>
                </div>

                <div class="table-section">
                    <h2>Consultation History</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Complaint</th>
                                <th>Diagnosis</th>
                                <th>Physician</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($consultations)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">No consultations found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($consultations as $c): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($c['consultation_date']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($c['chief_complaint'], 0, 30) . '...'); ?></td>
                                        <td><?php echo htmlspecialchars(substr($c['diagnosis'], 0, 30) . '...'); ?></td>
                                        <td><?php echo htmlspecialchars($c['attending_physician']); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
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

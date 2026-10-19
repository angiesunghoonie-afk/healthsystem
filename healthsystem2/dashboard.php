<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';
require_once 'classes/Patient.php';
require_once 'classes/Consultation.php';
require_once 'classes/MedicalRecord.php';
require_once 'classes/Medicine.php';

$patient = new Patient($pdo);
$consultation = new Consultation($pdo);
$medicalRecord = new MedicalRecord($pdo);
$medicine = new Medicine($pdo);

$userRole = $_SESSION['role'] ?? 'staff';
$patientId = $_SESSION['patient_id'] ?? null;
$patientCount = $patient->getPatientCount();
$consultationCount = $consultation->getConsultationCount();
$recordCount = $medicalRecord->getMedicalRecordCount();
$medicineCount = $medicine->getMedicineCount();
$recentConsultations = $consultation->getRecentConsultations(5);

// For patients, get their specific data
$patientData = null;
$myConsultations = [];
$myRecords = [];

if ($patientId && in_array($userRole, ['patient', 'guardian'])) {
    $patientData = $patient->getPatientById($patientId);
    $myConsultations = $consultation->getConsultationsByPatient($patientId);
    $myRecords = $medicalRecord->getMedicalRecordsByPatient($patientId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/header.php'; ?>
</head>
<body>

    <?php include 'components/navbar2.php'; ?>
    
    <div class="dashboard-container">
        <?php include 'components/sidebar.php'; ?>
        
        <main class="dashboard-content">
            <?php if ($userRole === 'admin'): ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon patients-icon">👥</div>
                        <div class="stat-info">
                            <h3>Total Patients</h3>
                            <p class="stat-number"><?php echo $patientCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon consultations-icon">🩺</div>
                        <div class="stat-info">
                            <h3>Consultations</h3>
                            <p class="stat-number"><?php echo $consultationCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon records-icon">📋</div>
                        <div class="stat-info">
                            <h3>Medical Records</h3>
                            <p class="stat-number"><?php echo $recordCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon inventory-icon">💊</div>
                        <div class="stat-info">
                            <h3>Medicine Items</h3>
                            <p class="stat-number"><?php echo $medicineCount; ?></p>
                        </div>
                    </div>
                </div>

                <div class="recent-activity">
                    <h2>Recent Consultations</h2>
                    <div class="activity-list">
                        <?php if (empty($recentConsultations)): ?>
                            <div class="activity-item">
                                <p class="activity-text">No recent consultations to display</p>
                                <p class="activity-time">-</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentConsultations as $consult): ?>
                                <div class="activity-item">
                                    <p class="activity-text">
                                        <strong><?php echo htmlspecialchars($consult['first_name'] . ' ' . $consult['last_name']); ?></strong> -
                                        <?php echo htmlspecialchars($consult['chief_complaint']); ?>
                                    </p>
                                    <p class="activity-time"><?php echo date('M d, Y', strtotime($consult['consultation_date'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="modules/patients.php" class="action-btn">Add Patient</a>
                        <a href="modules/consultations.php" class="action-btn">New Consultation</a>
                        <a href="modules/medical_records.php" class="action-btn">View Records</a>
                        <a href="modules/medicine_inventory.php" class="action-btn">Manage Inventory</a>
                    </div>
                </div>
            <?php elseif ($userRole === 'doctor'): ?>
                <div class="welcome-section">
                    <h1>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['username'] ?? 'Doctor'); ?></h1>
                    <p>Manage patient consultations and medical records.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon patients-icon">👥</div>
                        <div class="stat-info">
                            <h3>Total Patients</h3>
                            <p class="stat-number"><?php echo $patientCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon consultations-icon">🩺</div>
                        <div class="stat-info">
                            <h3>Consultations</h3>
                            <p class="stat-number"><?php echo $consultationCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon records-icon">📋</div>
                        <div class="stat-info">
                            <h3>Medical Records</h3>
                            <p class="stat-number"><?php echo $recordCount; ?></p>
                        </div>
                    </div>
                </div>

                <div class="recent-activity">
                    <h2>Recent Consultations</h2>
                    <div class="activity-list">
                        <?php if (empty($recentConsultations)): ?>
                            <div class="activity-item">
                                <p class="activity-text">No recent consultations to display</p>
                                <p class="activity-time">-</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentConsultations as $consult): ?>
                                <div class="activity-item">
                                    <p class="activity-text">
                                        <strong><?php echo htmlspecialchars($consult['first_name'] . ' ' . $consult['last_name']); ?></strong> -
                                        <?php echo htmlspecialchars($consult['chief_complaint']); ?>
                                    </p>
                                    <p class="activity-time"><?php echo date('M d, Y', strtotime($consult['consultation_date'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="modules/patients.php" class="action-btn">View Patients</a>
                        <a href="modules/consultations.php" class="action-btn">New Consultation</a>
                        <a href="modules/medical_records.php" class="action-btn">View Records</a>
                    </div>
                </div>

            <?php elseif ($userRole === 'staff'): ?>
                <div class="welcome-section">
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Staff'); ?></h1>
                    <p>Manage patient consultations and medical records.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon patients-icon">👥</div>
                        <div class="stat-info">
                            <h3>Total Patients</h3>
                            <p class="stat-number"><?php echo $patientCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon consultations-icon">🩺</div>
                        <div class="stat-info">
                            <h3>Consultations</h3>
                            <p class="stat-number"><?php echo $consultationCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon records-icon">📋</div>
                        <div class="stat-info">
                            <h3>Medical Records</h3>
                            <p class="stat-number"><?php echo $recordCount; ?></p>
                        </div>
                    </div>
                </div>

                <div class="recent-activity">
                    <h2>Recent Consultations</h2>
                    <div class="activity-list">
                        <?php if (empty($recentConsultations)): ?>
                            <div class="activity-item">
                                <p class="activity-text">No recent consultations to display</p>
                                <p class="activity-time">-</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentConsultations as $consult): ?>
                                <div class="activity-item">
                                    <p class="activity-text">
                                        <strong><?php echo htmlspecialchars($consult['first_name'] . ' ' . $consult['last_name']); ?></strong> -
                                        <?php echo htmlspecialchars($consult['chief_complaint']); ?>
                                    </p>
                                    <p class="activity-time"><?php echo date('M d, Y', strtotime($consult['consultation_date'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="modules/patients.php" class="action-btn">Add Patient</a>
                        <a href="modules/consultations.php" class="action-btn">New Consultation</a>
                        <a href="modules/medical_records.php" class="action-btn">View Records</a>
                    </div>
                </div>

            <?php elseif (in_array($userRole, ['patient', 'guardian'])): ?>
                <div class="welcome-section">
                    <h1>Welcome, <?php echo htmlspecialchars($patientData['first_name'] . ' ' . $patientData['last_name'] ?? 'Patient'); ?></h1>
                    <p>View your medical records and consultation history.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon consultations-icon">🩺</div>
                        <div class="stat-info">
                            <h3>My Consultations</h3>
                            <p class="stat-number"><?php echo count($myConsultations); ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon records-icon">📋</div>
                        <div class="stat-info">
                            <h3>Medical Records</h3>
                            <p class="stat-number"><?php echo count($myRecords); ?></p>
                        </div>
                    </div>
                </div>

                <div class="recent-activity">
                    <h2>My Consultation History</h2>
                    <div class="activity-list">
                        <?php if (empty($myConsultations)): ?>
                            <div class="activity-item">
                                <p class="activity-text">No consultations found</p>
                                <p class="activity-time">-</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($myConsultations as $consult): ?>
                                <div class="activity-item">
                                    <p class="activity-text">
                                        <strong><?php echo htmlspecialchars($consult['chief_complaint']); ?></strong>
                                    </p>
                                    <p class="activity-time"><?php echo date('M d, Y', strtotime($consult['consultation_date'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="#" class="action-btn">View My Records</a>
                        <a href="#" class="action-btn">Book Appointment</a>
                    </div>
                </div>

            <?php elseif ($userRole === 'visitor'): ?>
                <div class="welcome-section">
                    <h1>Welcome Visitor</h1>
                    <p>View health center information and services.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon patients-icon">👥</div>
                        <div class="stat-info">
                            <h3>Total Patients</h3>
                            <p class="stat-number"><?php echo $patientCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon consultations-icon">🩺</div>
                        <div class="stat-info">
                            <h3>Consultations</h3>
                            <p class="stat-number"><?php echo $consultationCount; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon records-icon">📋</div>
                        <div class="stat-info">
                            <h3>Medical Records</h3>
                            <p class="stat-number"><?php echo $recordCount; ?></p>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h2>Health Center Services</h2>
                    <ul>
                        <li>General Consultations</li>
                        <li>Medical Check-ups</li>
                        <li>Prescription Services</li>
                        <li>Health Education</li>
                        <li>Vaccination Programs</li>
                    </ul>
                </div>

                <div class="recent-activity">
                    <h2>Recent Consultations</h2>
                    <div class="activity-list">
                        <?php if (empty($recentConsultations)): ?>
                            <div class="activity-item">
                                <p class="activity-text">No recent consultations to display</p>
                                <p class="activity-time">-</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentConsultations as $consult): ?>
                                <div class="activity-item">
                                    <p class="activity-text">
                                        <strong><?php echo htmlspecialchars($consult['first_name'] . ' ' . $consult['last_name']); ?></strong> -
                                        <?php echo htmlspecialchars($consult['chief_complaint']); ?>
                                    </p>
                                    <p class="activity-time"><?php echo date('M d, Y', strtotime($consult['consultation_date'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="register.php" class="action-btn">Register as Patient</a>
                        <a href="login.php" class="action-btn">Login</a>
                    </div>
                </div>

            <?php endif; ?>
        </main>
    </div>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>
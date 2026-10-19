<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../config/database.php';
require_once '../classes/Patient.php';

$patient = new Patient($pdo);
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $patients = $patient->searchPatients($search);
} else {
    $patients = $patient->getAllPatients();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $data = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'middle_name' => $_POST['middle_name'],
            'birth_date' => $_POST['birth_date'],
            'gender' => $_POST['gender'],
            'address' => $_POST['address'],
            'contact_number' => $_POST['contact_number'],
            'emergency_contact' => $_POST['emergency_contact'],
            'emergency_contact_number' => $_POST['emergency_contact_number']
        ];
        
        if ($patient->addPatient($data)) {
            header('Location: patients.php?success=added');
            exit();
        }
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        if ($patient->deletePatient($id)) {
            header('Location: patients.php?success=deleted');
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
                <h1>Patient Management</h1>
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search patients..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="module-content">
                <div class="form-section">
                    <h2>Add New Patient</h2>
                    <form method="POST" class="module-form">
                        <input type="hidden" name="action" value="add">
                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" required placeholder="Enter first name" autocomplete="given-name">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" required placeholder="Enter last name" autocomplete="family-name">
                            </div>
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" placeholder="Enter middle name" autocomplete="additional-name">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Birth Date</label>
                                <input type="date" name="birth_date" required>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" required placeholder="Enter address" autocomplete="street-address">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="tel" name="contact_number" required placeholder="Enter contact number" autocomplete="tel">
                            </div>
                            <div class="form-group">
                                <label>Emergency Contact</label>
                                <input type="text" name="emergency_contact" required placeholder="Enter emergency contact name">
                            </div>
                            <div class="form-group">
                                <label>Emergency Contact Number</label>
                                <input type="tel" name="emergency_contact_number" required placeholder="Enter emergency contact number" autocomplete="tel">
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">Add Patient</button>
                    </form>
                </div>

                <div class="table-section">
                    <h2>Patient List</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Birth Date</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($patients)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">No patients found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($patients as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['gender']); ?></td>
                                        <td><?php echo htmlspecialchars($p['birth_date']); ?></td>
                                        <td><?php echo htmlspecialchars($p['contact_number']); ?></td>
                                        <td><?php echo htmlspecialchars($p['address']); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
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

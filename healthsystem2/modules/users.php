<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once '../config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE :search OR email LIKE :search OR role LIKE :search ORDER BY created_at DESC");
    $stmt->execute([':search' => "%$search%"]);
    $users = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: users.php?success=deleted');
        exit();
    } elseif ($_POST['action'] == 'update_role') {
        $id = $_POST['id'];
        $role = $_POST['role'];
        $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
        $stmt->execute([':role' => $role, ':id' => $id]);
        header('Location: users.php?success=updated');
        exit();
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
                <h1>User Management</h1>
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="table-section">
                <h2>All Users</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Patient ID</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="update_role">
                                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                            <select name="role" onchange="this.form.submit()" style="padding: 5px; border-radius: 5px;">
                                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                <option value="doctor" <?php echo $user['role'] === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                                                <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                                <option value="patient" <?php echo $user['role'] === 'patient' ? 'selected' : ''; ?>>Patient</option>
                                                <option value="visitor" <?php echo $user['role'] === 'visitor' ? 'selected' : ''; ?>>Visitor</option>
                                                <option value="guardian" <?php echo $user['role'] === 'guardian' ? 'selected' : ''; ?>>Guardian</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><?php echo isset($user['patient_id']) && $user['patient_id'] ? htmlspecialchars($user['patient_id']) : '-'; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <?php if ($user['username'] !== 'admin'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #999;">Cannot delete</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <?php include '../components/footer.php'; ?>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../config/database.php';
require_once '../classes/Medicine.php';

$medicine = new Medicine($pdo);
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $medicines = $medicine->searchMedicines($search);
} else {
    $medicines = $medicine->getAllMedicines();
}

// Get recent stock movements
$stmt = $pdo->query("SELECT sm.*, mi.medicine_name, u.username FROM stock_movements sm JOIN medicine_inventory mi ON sm.medicine_id = mi.id JOIN users u ON sm.user_id = u.id ORDER BY sm.created_at DESC LIMIT 10");
$stockMovements = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $data = [
            'medicine_name' => $_POST['medicine_name'],
            'generic_name' => $_POST['generic_name'],
            'category' => $_POST['category'],
            'quantity' => $_POST['quantity'],
            'unit' => $_POST['unit'],
            'expiry_date' => $_POST['expiry_date'],
            'manufacturer' => $_POST['manufacturer'],
            'supplier' => $_POST['supplier'],
            'price' => $_POST['price']
        ];

        if ($medicine->addMedicine($data)) {
            header('Location: medicine_inventory.php?success=added');
            exit();
        }
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        if ($medicine->deleteMedicine($id)) {
            header('Location: medicine_inventory.php?success=deleted');
            exit();
        }
    } elseif ($_POST['action'] == 'stock_in') {
        $id = $_POST['id'];
        $quantity = $_POST['quantity'];
        $reason = $_POST['reason'];

        $stmt = $pdo->prepare("UPDATE medicine_inventory SET quantity = quantity + :quantity WHERE id = :id");
        $stmt->execute([':quantity' => $quantity, ':id' => $id]);

        $stmt = $pdo->prepare("INSERT INTO stock_movements (medicine_id, movement_type, quantity, reason, user_id) VALUES (:medicine_id, 'in', :quantity, :reason, :user_id)");
        $stmt->execute([
            ':medicine_id' => $id,
            ':quantity' => $quantity,
            ':reason' => $reason,
            ':user_id' => $_SESSION['user_id']
        ]);

        header('Location: medicine_inventory.php?success=stock_in');
        exit();
    } elseif ($_POST['action'] == 'stock_out') {
        $id = $_POST['id'];
        $quantity = $_POST['quantity'];
        $reason = $_POST['reason'];

        $stmt = $pdo->prepare("UPDATE medicine_inventory SET quantity = quantity - :quantity WHERE id = :id AND quantity >= :quantity");
        $stmt->execute([':quantity' => $quantity, ':id' => $id]);

        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare("INSERT INTO stock_movements (medicine_id, movement_type, quantity, reason, user_id) VALUES (:medicine_id, 'out', :quantity, :reason, :user_id)");
            $stmt->execute([
                ':medicine_id' => $id,
                ':quantity' => $quantity,
                ':reason' => $reason,
                ':user_id' => $_SESSION['user_id']
            ]);
            header('Location: medicine_inventory.php?success=stock_out');
        } else {
            header('Location: medicine_inventory.php?error=insufficient_stock');
        }
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
                <h1>Medicine Inventory</h1>
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search medicines..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="stock-management" style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 32px; border: 1px solid #e2e8f0;">
                <h2 style="margin: 0 0 20px 0; font-size: 20px; color: #1d4ed8; font-weight: 700;">Quick Stock Management</h2>
                <form method="POST" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                    <input type="hidden" name="action" id="quickAction">
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Select Medicine</label>
                        <select name="id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                            <option value="">Select Medicine</option>
                            <?php foreach ($medicines as $m): ?>
                                <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['medicine_name']); ?> (Stock: <?php echo $m['quantity']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="width: 150px;">
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Type</label>
                        <select name="type" required onchange="updateQuickAction()" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                        </select>
                    </div>
                    <div style="width: 120px;">
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Quantity</label>
                        <input type="number" name="quantity" required min="1" placeholder="Qty" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Reason</label>
                        <input type="text" name="reason" required placeholder="Enter reason" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                    </div>
                    <button type="submit" class="submit-btn" style="padding: 12px 24px; font-size: 15px;">Submit</button>
                </form>
            </div>

            <div class="add-medicine" style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 32px; border: 1px solid #e2e8f0;">
                <h2 style="margin: 0 0 20px 0; font-size: 20px; color: #1d4ed8; font-weight: 700;">Add New Medicine</h2>
                <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <input type="hidden" name="action" value="add">
                    <div>
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Medicine Name</label>
                        <input type="text" name="medicine_name" required placeholder="Medicine name" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                    </div>
                    <div>
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Generic Name</label>
                        <input type="text" name="generic_name" placeholder="Generic name" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                    </div>
                    <div>
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Category</label>
                        <select name="category" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                            <option value="">Select</option>
                            <option value="Antibiotics">Antibiotics</option>
                            <option value="Pain Relief">Pain Relief</option>
                            <option value="Vitamins">Vitamins</option>
                            <option value="Cardiovascular">Cardiovascular</option>
                            <option value="Respiratory">Respiratory</option>
                            <option value="Gastrointestinal">Gastrointestinal</option>
                            <option value="Dermatological">Dermatological</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Quantity</label>
                        <input type="number" name="quantity" required min="0" placeholder="Initial stock" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                    </div>
                    <div>
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Unit</label>
                        <select name="unit" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                            <option value="">Select</option>
                            <option value="Tablets">Tablets</option>
                            <option value="Capsules">Capsules</option>
                            <option value="ml">ml</option>
                            <option value="mg">mg</option>
                            <option value="Pieces">Pieces</option>
                            <option value="Bottles">Bottles</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Expiry Date</label>
                        <input type="date" name="expiry_date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                    </div>
                    <div>
                        <label style="display: block; color: #1d4ed8; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Price</label>
                        <input type="number" name="price" step="0.01" min="0" required placeholder="Price" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <button type="submit" class="submit-btn" style="padding: 12px 24px; font-size: 15px;">Add Medicine</button>
                    </div>
                </form>
            </div>

            <div class="module-content">
                <div class="form-section">
                    <h2>Add Medicine</h2>
                    <form method="POST" class="module-form">
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label>Medicine Name</label>
                            <input type="text" name="medicine_name" required placeholder="Enter medicine name">
                        </div>
                        <div class="form-group">
                            <label>Generic Name</label>
                            <input type="text" name="generic_name" placeholder="Enter generic name">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" required>
                                <option value="">Select Category</option>
                                <option value="Antibiotics">Antibiotics</option>
                                <option value="Pain Relief">Pain Relief</option>
                                <option value="Vitamins">Vitamins</option>
                                <option value="Cardiovascular">Cardiovascular</option>
                                <option value="Respiratory">Respiratory</option>
                                <option value="Gastrointestinal">Gastrointestinal</option>
                                <option value="Dermatological">Dermatological</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" required min="0" placeholder="Enter quantity">
                            </div>
                            <div class="form-group">
                                <label>Unit</label>
                                <select name="unit" required>
                                    <option value="">Select</option>
                                    <option value="Tablets">Tablets</option>
                                    <option value="Capsules">Capsules</option>
                                    <option value="ml">ml</option>
                                    <option value="mg">mg</option>
                                    <option value="Pieces">Pieces</option>
                                    <option value="Bottles">Bottles</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" required>
                        </div>
                        <div class="form-group">
                            <label>Manufacturer</label>
                            <input type="text" name="manufacturer" placeholder="Enter manufacturer name">
                        </div>
                        <div class="form-group">
                            <label>Supplier</label>
                            <input type="text" name="supplier" placeholder="Enter supplier name">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" min="0" required placeholder="Enter price">
                        </div>
                        <button type="submit" class="submit-btn">Add Medicine</button>
                    </form>
                </div>

                <div class="table-section">
                    <h2>Inventory List</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Expiry Date</th>
                                <th>Price</th>
                                <th>Stock Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($medicines)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center;">No medicines found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($medicines as $m): ?>
                                    <?php
                                    $stockStatus = $m['quantity'] > 0 ? 'In Stock' : 'Out of Stock';
                                    $statusColor = $m['quantity'] > 0 ? '#10b981' : '#ef4444';
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($m['medicine_name']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($m['generic_name']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($m['category']); ?></td>
                                        <td><?php echo htmlspecialchars($m['quantity']); ?></td>
                                        <td><?php echo htmlspecialchars($m['unit']); ?></td>
                                        <td><?php echo htmlspecialchars($m['expiry_date']); ?></td>
                                        <td>₱<?php echo number_format($m['price'], 2); ?></td>
                                        <td>
                                            <span style="color: <?php echo $statusColor; ?>; font-weight: bold; padding: 4px 8px; border-radius: 4px; background: <?php echo $m['quantity'] > 0 ? '#d1fae5' : '#fee2e2'; ?>;">
                                                <?php echo $stockStatus; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?')" style="padding: 8px 12px; font-size: 12px;">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-section" style="margin-top: 32px;">
                    <h2>Recent Stock Movements</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Reason</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stockMovements)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">No stock movements found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($stockMovements as $movement): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($movement['medicine_name']); ?></td>
                                        <td>
                                            <span style="color: <?php echo $movement['movement_type'] === 'in' ? '#10b981' : '#ef4444'; ?>; font-weight: bold;">
                                                <?php echo strtoupper($movement['movement_type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $movement['quantity']; ?></td>
                                        <td><?php echo htmlspecialchars($movement['reason']); ?></td>
                                        <td><?php echo htmlspecialchars($movement['username']); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($movement['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="stockModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeStockModal()">&times;</span>
            <h2 id="modalTitle">Stock Movement</h2>
            <form method="POST">
                <input type="hidden" name="action" id="modalAction">
                <input type="hidden" name="id" id="modalMedicineId">
                <div class="form-group">
                    <label>Medicine</label>
                    <input type="text" id="modalMedicineName" readonly style="background: #f3f4f6;">
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" required min="1">
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" name="reason" required placeholder="Enter reason">
                </div>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>

    <style>
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 12px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }
    </style>

    <script>
        function openStockModal(medicineId, medicineName, type) {
            document.getElementById('stockModal').style.display = 'block';
            document.getElementById('modalMedicineId').value = medicineId;
            document.getElementById('modalMedicineName').value = medicineName;
            document.getElementById('modalAction').value = 'stock_' + type;
            document.getElementById('modalTitle').textContent = type === 'in' ? 'Stock In' : 'Stock Out';
        }

        function closeStockModal() {
            document.getElementById('stockModal').style.display = 'none';
        }

        function updateQuickAction() {
            const type = document.querySelector('select[name="type"]').value;
            document.getElementById('quickAction').value = 'stock_' + type;
        }

        window.onload = function() {
            updateQuickAction();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('stockModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    <?php include '../components/footer.php'; ?>
</body>
</html>

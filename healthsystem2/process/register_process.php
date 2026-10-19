<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Patient.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $role = $_POST['role'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: ../register.php');
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);

        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Username or email already exists';
            header('Location: ../register.php');
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $patient_id = null;

        // If registering as patient, create patient record first
        if ($role === 'patient') {
            $patient = new Patient($pdo);
            $patientData = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'middle_name' => $_POST['middle_name'],
                'birth_date' => $_POST['birth_date'],
                'gender' => $_POST['gender'],
                'address' => $_POST['address'],
                'contact_number' => $contact_number,
                'emergency_contact' => $_POST['emergency_contact'],
                'emergency_contact_number' => $_POST['emergency_contact_number']
            ];

            if ($patient->addPatient($patientData)) {
                $patient_id = $pdo->lastInsertId();
            } else {
                $_SESSION['error'] = 'Failed to create patient record';
                header('Location: ../register.php');
                exit();
            }
        }

        // Create user account
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, patient_id) VALUES (:username, :password, :email, :role, :patient_id)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashed_password,
            ':email' => $email,
            ':role' => $role,
            ':patient_id' => $patient_id
        ]);

        $_SESSION['success'] = 'Registration successful! Please login.';
        header('Location: ../login.php');
        exit();

    } catch(PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header('Location: ../register.php');
        exit();
    }
} else {
    header('Location: ../register.php');
    exit();
}
?>

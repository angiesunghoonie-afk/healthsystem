<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE id = :id");
            $stmt->execute([
                ':token' => $token,
                ':expiry' => $expiry,
                ':id' => $user['id']
            ]);
            
            $_SESSION['success'] = 'Password reset link has been sent to your email (simulation)';
            header('Location: ../login.php');
            exit();
        } else {
            $_SESSION['error'] = 'Email not found';
            header('Location: ../forgot_password.php');
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header('Location: ../forgot_password.php');
        exit();
    }
} else {
    header('Location: ../forgot_password.php');
    exit();
}
?>

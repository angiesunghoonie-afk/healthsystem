<?php
require_once 'config/database.php';

$username = 'admin';
$password = 'admin123';
$email = 'admin@healthcenter.com';
$role = 'admin';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE users SET password = :password, email = :email, role = :role WHERE username = :username");
        $stmt->execute([
            ':password' => $hashedPassword,
            ':email' => $email,
            ':role' => $role,
            ':username' => $username
        ]);
        echo "Admin account updated successfully!<br>";
    } else {
        // Create new admin
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':email' => $email,
            ':role' => $role
        ]);
        echo "Admin account created successfully!<br>";
    }

    echo "<br><strong>Login Credentials:</strong><br>";
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password . "<br>";
    echo "<br><a href='login.php'>Go to Login Page</a>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

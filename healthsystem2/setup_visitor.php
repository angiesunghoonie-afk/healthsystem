<?php
require_once 'config/database.php';

$username = 'visitor';
$password = 'visitor123';
$email = 'visitor@healthcenter.com';
$role = 'visitor';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if visitor already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update existing visitor
        $stmt = $pdo->prepare("UPDATE users SET password = :password, email = :email, role = :role WHERE username = :username");
        $stmt->execute([
            ':password' => $hashedPassword,
            ':email' => $email,
            ':role' => $role,
            ':username' => $username
        ]);
        echo "Visitor account updated successfully!<br>";
    } else {
        // Create new visitor
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':email' => $email,
            ':role' => $role
        ]);
        echo "Visitor account created successfully!<br>";
    }

    echo "<br><strong>Login Credentials:</strong><br>";
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password . "<br>";
    echo "<br><a href='login.php'>Go to Login Page</a>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

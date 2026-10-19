<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password hash for '$password':<br>";
echo $hash;
echo "<br><br>Copy this hash and replace '$2y$10$YourActualHashHereForAdmin123' in database_setup.sql";
?>

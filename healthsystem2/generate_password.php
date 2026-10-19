<?php
// Run this file to generate a password hash
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password hash for '$password':\n";
echo $hash;
?>

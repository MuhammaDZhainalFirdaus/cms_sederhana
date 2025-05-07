<?php
require 'config/database.php';

$newpass = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->execute([$newpass]);
echo "Password admin berhasil direset menjadi: admin123";
?> 
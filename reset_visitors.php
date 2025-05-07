<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo 'Forbidden';
    exit();
}

$pdo->exec('TRUNCATE TABLE visitors');
echo 'OK'; 
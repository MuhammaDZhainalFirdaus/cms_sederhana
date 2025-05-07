<?php
require_once 'config/database.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT title, DATE(created_at) as date FROM articles");
$events = [];
while ($row = $stmt->fetch()) {
    $events[] = [
        'title' => $row['title'],
        'start' => $row['date'],
        'allDay' => true
    ];
}
echo json_encode($events); 
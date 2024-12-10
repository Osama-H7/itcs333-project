<?php
require 'database.php';

$roomId = $_GET['room_id'];
$startTime = $_GET['start'];
$endTime = $_GET['end'];

// SQL to check for overlapping reservations
$sql = "SELECT * FROM reservations WHERE room_id = :room_id AND 
        ((start_time < :end_time) AND (end_time > :start_time))";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'room_id' => $roomId,
    'start_time' => $startTime,
    'end_time' => $endTime
]);

$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return availability status
echo json_encode(['available' => count($reservations) === 0]);
?>
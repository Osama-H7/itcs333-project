<?php
session_start();
require 'database.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $roomId = $_GET['id'];
    
    $sql = "DELETE FROM rooms WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $roomId]);

    header("Location: room_schedule.php");
    exit();
}
?>
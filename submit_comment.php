<?php
session_start();
require 'database.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$roomId = $_POST['room_id'] ?? null;
$commentText = $_POST['comment'] ?? null;

if ($roomId && $commentText) {
    
    $stmt = $pdo->prepare("INSERT INTO comments (room_id, user_id, comment_text) VALUES (:room_id, :user_id, :comment_text)");
    $stmt->execute([
        'room_id' => $roomId,
        'user_id' => $_SESSION['user_id'],
        'comment_text' => $commentText
    ]);

    
    $userId = $_SESSION['user_id'];
    $userStmt = $pdo->prepare("SELECT email FROM users WHERE id = :user_id");
    $userStmt->execute(['user_id' => $userId]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    
    $notificationMessage = "New comment from " . htmlspecialchars($user['email']) . " on room '" . htmlspecialchars($roomId) . "': " . htmlspecialchars($commentText);
    
    
    $notificationStmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)");
    $notificationStmt->execute([
        'user_id' => $userId, // Notify the user who made the comment
        'message' => $notificationMessage
    ]);

     
    header("Location: view_comments.php?id=$roomId&comment_submitted=1");
    exit();
} else {
    header("Location: browse_rooms.php");
    exit();
}
?>
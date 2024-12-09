<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$roomId = $_GET['room_id'];

// Fetch comments for the selected room
$sql = "SELECT c.comment_text, c.created_at, u.username 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.room_id = :room_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['room_id' => $roomId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments for Room</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f5;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #003366;
            text-align: center;
            margin-bottom: 30px;
        }
        .comment-list {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
        }
        .comment {
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            transition: background-color 0.2s;
        }
        .comment:hover {
            background-color: #f9f9f9;
        }
        .comment strong {
            color: #007bff;
        }
        .comment small {
            display: block;
            color: #666;
            font-size: 0.9em;
        }
        .back-button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin: 20px auto;
            text-decoration: none;
            width: 200px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Comments for Room <?php echo htmlspecialchars($roomId); ?></h1>
    <div class="comment-list">
        <?php if (empty($comments)): ?>
            <p>No comments available for this room.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                    <small>Posted on: <?php echo htmlspecialchars($comment['created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <a class="back-button" href="browse_rooms.php">Back to Available Rooms</a>
</body>
</html>
<?php
session_start();
require 'database.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the notification details
$notificationId = $_GET['id'] ?? null;
$sqlNotification = "SELECT n.id, n.message, n.created_at, c.comment_text, c.user_id AS commenter_id, u.email AS commenter_email, rm.name AS room_name 
                    FROM notifications n 
                    LEFT JOIN comments c ON n.message LIKE CONCAT('%', c.room_id, '%')
                    LEFT JOIN rooms rm ON c.room_id = rm.id
                    LEFT JOIN users u ON c.user_id = u.id
                    WHERE n.id = :id AND n.user_id = :user_id";
$stmtNotification = $pdo->prepare($sqlNotification);
$stmtNotification->execute(['id' => $notificationId, 'user_id' => $_SESSION['user_id']]);
$notification = $stmtNotification->fetch(PDO::FETCH_ASSOC);

// Handle marking the notification as read
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $markReadSql = "UPDATE notifications SET is_read = 1 WHERE id = :id";
    $markReadStmt = $pdo->prepare($markReadSql);
    $markReadStmt->execute(['id' => $notificationId]);
    
    header("Location: notifications.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 20px;
        }
        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .notification-detail {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
        .mark-read-button {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }
        .mark-read-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Notification Details</h1>
    </div>

    <div class="notification-detail">
        <?php if ($notification): ?>
            <p><strong>Message:</strong> <?php echo htmlspecialchars($notification['message']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($notification['created_at']); ?></p>
            <?php if ($notification['comment_text']): ?>
                <p><strong>Comment:</strong> <?php echo htmlspecialchars($notification['comment_text']); ?></p>
                <p><strong>Commenter Email:</strong> <?php echo htmlspecialchars($notification['commenter_email']); ?></p>
                <p><strong>Room:</strong> <?php echo htmlspecialchars($notification['room_name']); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p>No details found for this notification.</p>
        <?php endif; ?>
        <form method="POST" action="">
            <button type="submit" class="mark-read-button">Mark as Read</button>
        </form>
        <a href="notifications.php" class="back-link">Back to Notifications</a>
    </div>
</body>
</html>
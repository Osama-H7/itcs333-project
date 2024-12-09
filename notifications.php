<?php
session_start();
require 'database.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch notifications for the logged-in user
$userId = $_SESSION['user_id'];
$sqlNotifications = "SELECT n.id, n.message, n.created_at, c.comment_text, c.user_id AS commenter_id, rm.name AS room_name, n.is_read 
                    FROM notifications n 
                    LEFT JOIN comments c ON n.message LIKE CONCAT('%', c.room_id, '%') 
                    LEFT JOIN rooms rm ON c.room_id = rm.id 
                    WHERE n.user_id = :user_id 
                    ORDER BY n.created_at DESC";
$stmtNotifications = $pdo->prepare($sqlNotifications);
$stmtNotifications->execute(['user_id' => $userId]);
$notifications = $stmtNotifications->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .header img {
            width: 120px;
        }
        .logout-link, .back-link {
            position: absolute;
            top: 20px;
            background-color: #00509e;
            color: white;
            text-decoration: none;
            font-size: 1em;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .logout-link {
            right: 20px;
        }
        .back-link {
            left: 20px;
        }
        .logout-link:hover, .back-link:hover {
            background-color: #003366;
        }
        .notifications {
            margin-top: 20px;
        }
        .notification {
            margin-bottom: 15px;
            padding: 20px;
            border-left: 6px solid #007bff;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .notification:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .notification h4 {
            margin: 0 0 5px;
            font-size: 1.2em;
            color: #333;
        }
        .notification p {
            margin: 5px 0;
        }
        .notification .comment {
            font-style: italic;
            color: #555;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
        .mark-read-button {
            background-color: #dc3545; /* Red */
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .mark-read-button:hover {
            background-color: #c82333; /* Darker red */
        }
        .mark-read-button.read {
            background-color: #28a745; /* Green */
        }
        .success-message {
            margin-top: 10px;
            color: #28a745;
            font-weight: bold;
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="uploads/logo.png" alt="University of Bahrain Logo">
        <h1>Your Notifications</h1>
        <a href="logout.php" class="logout-link">Logout</a>
        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>

    <div class="notifications">
        <?php if (empty($notifications)): ?>
            <p>No new notifications.</p>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification" style="<?php echo $notification['is_read'] ? '' : 'border-left-color: #007bff;'; ?>">
                    <h4>
                        <a href="notification_detail.php?id=<?php echo $notification['id']; ?>" style="color: #007bff; text-decoration: none;">
                            <?php echo htmlspecialchars($notification['message']); ?>
                        </a>
                    </h4>
                    <p><em><?php echo htmlspecialchars($notification['created_at']); ?></em></p>
                    <?php if ($notification['comment_text']): ?>
                        <p class="comment"><strong>Comment:</strong> <?php echo htmlspecialchars($notification['comment_text']); ?></p>
                        <p><strong>Room:</strong> <?php echo htmlspecialchars($notification['room_name']); ?></p>
                    <?php endif; ?>
                    <form method="POST" action="notification_detail.php?id=<?php echo $notification['id']; ?>" style="display: inline;">
                        <button type="submit" class="mark-read-button<?php echo $notification['is_read'] ? ' read' : ''; ?>" 
                            onclick="this.classList.add('read'); this.innerHTML='Ensure I read it'; this.nextElementSibling.style.display='block';">
                            Mark as Read
                        </button>
                        <span class="success-message">You have read this notification.</span>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> IT College, University of Bahrain
    </footer>
</body>
</html>
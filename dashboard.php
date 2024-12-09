<?php
session_start();
require 'database.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $notificationId = (int)$_GET['mark_read'];
    $updateSql = "UPDATE notifications SET is_read = 1 WHERE id = :id";

    try {
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute(['id' => $notificationId]);
    } catch (PDOException $e) {
        error_log("Update error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef2f5;
        }

        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header img {
            width: 120px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 40px auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 2em;
            color: #003366;
        }

        p {
            font-size: 1em;
            color: #555;
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background-color: #003366;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .button:hover {
            background-color: #00509e;
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }

        @media (max-width: 600px) {
            .button {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="header">

    </div>
    <div class="container">
        <h1>Dashboard</h1>
        <p>Welcome to the IT College Reservation System</p>

        <div class="action-buttons">
            <a href="browse_rooms.php" class="button"><i class="fas fa-bed"></i> Browse Rooms</a>
            <a href="view_reservations.php" class="button"><i class="fas fa-list"></i> View My Reservations</a>
            <a href="profile.php" class="button"><i class="fas fa-user-edit"></i> Profile</a>
            <a href="room_schedule.php" class="button"><i class="fas fa-calendar-alt"></i> Room Schedule Management</a>
            <a href="reporting_options.php" class="button"><i class="fas fa-chart-line"></i> Reporting Options</a>
            <?php if ($isAdmin): ?>
                <a href="admin.php" class="button"><i class="fas fa-cogs"></i> Manage Rooms</a>
            <?php endif; ?>
            <a href="submit_feedback.php" class="button"><i class="fas fa-comments"></i> Give Feedback</a>
            <a href="notifications.php" class="button"><i class="fas fa-bell"></i> Notifications</a>
            <a href="logout.php" class="button"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>

        <?php if (isset($_GET['feedback_submitted'])): ?>
            <p style="color: green;">Feedback submitted successfully!</p>
        <?php endif; ?>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> IT College, University of Bahrain
    </footer>
</body>
</html>
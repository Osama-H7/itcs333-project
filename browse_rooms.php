<?php
session_start();
require 'database.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);


$sql = "SELECT id, name, capacity, features, status FROM rooms";
try {
    $stmt = $pdo->query($sql);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f5;
            margin: 0;
            padding: 20px;
        }

        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header img {
            width: 120px;
        }

        .logout-link, .dashboard-link {
            position: absolute;
            top: 20px;
            color: white;
            text-decoration: none;
            font-size: 1em;
            transition: color 0.3s;
        }

        .logout-link {
            right: 120px;
        }

        .dashboard-link {
            right: 20px;
        }

        .logout-link:hover, .dashboard-link:hover {
            color: #ffcccc;
        }

        h1 {
            color: #003366;
            text-align: center;
            margin: 20px 0;
        }

        .room-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .room-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: calc(33.333% - 20px);
            box-sizing: border-box;
            transition: transform 0.3s;
        }

        .room-card:hover {
            transform: translateY(-5px);
        }

        .room-card h2 {
            margin: 0 0 10px;
            color: #007bff;
        }

        .room-card p {
            margin: 5px 0;
            color: #555;
        }

        .reserve-button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .reserve-button:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .room-card {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .room-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a class="logout-link" href="logout.php">Log Out</a>
        <a class="dashboard-link" href="dashboard.php">Dashboard</a>
        <img src="https://upload.wikimedia.org/wikipedia/en/7/71/University_of_Bahrain_logo.png" alt="University of Bahrain Logo">
    </div>
    <h1>Available Rooms</h1>
    <div class="room-list">
        <?php if (empty($rooms)): ?>
            <p style="text-align:center; width: 100%;">No rooms available at the moment.</p>
            <?php var_dump($rooms); // Debugging line ?>
        <?php else: ?>
            <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <h2><?php echo htmlspecialchars($room['name']); ?></h2>
                    <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
                    <p><strong>Features:</strong> <?php echo htmlspecialchars($room['features']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($room['status']); ?></p>
                    <a class="reserve-button" href="reserve_room.php?id=<?php echo $room['id']; ?>">Reserve Now</a>
                    <div class="comment-section">
                        <h3>Comments</h3>
                        <a class="reserve-button" href="view_comments.php?id=<?php echo $room['id']; ?>">View Comments</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>

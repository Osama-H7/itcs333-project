<?php
session_start();
require 'database.php';

$rooms = [];
$searchTerm = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST['searchTerm'];
    $sql = "SELECT id, name, capacity, features, status FROM rooms 
            WHERE name LIKE :searchTerm OR features LIKE :searchTerm";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['searchTerm' => "%$searchTerm%"]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Rooms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f5;
            margin: 0;
            padding: 20px;
        }
        nav {
            background-color: #003366;
            padding: 10px;
            text-align: center;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        nav ul li a:hover {
            text-decoration: underline;
        }
        h1 {
            color: #003366;
            text-align: center;
        }
        .room-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .room-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
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
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="register.php">Home</a></li>
            <li><a href="search.php">Search Rooms</a></li>
            <li><a href="FAQ.php">FAQ</a></li>
            <li><a href="help.php">Help</a></li>
        </ul>
    </nav>
    <h1>Search Rooms</h1>
    <form method="post" style="text-align:center;">
        <input type="text" name="searchTerm" placeholder="Search by room name or features" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>
    <div class="room-list">
        <?php if (empty($rooms)): ?>
            <p>No rooms found.</p>
        <?php else: ?>
            <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <h2><?php echo htmlspecialchars($room['name']); ?></h2>
                    <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
                    <p><strong>Features:</strong> <?php echo htmlspecialchars($room['features']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($room['status']); ?></p>
                    <a class="reserve-button" href="reserve_room.php?id=<?php echo $room['id']; ?>">Reserve Now</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
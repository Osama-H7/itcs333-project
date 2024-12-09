<?php
session_start();
require 'database.php';

$roomId = $_GET['id'] ?? null;

if ($roomId) {
    $sql = "SELECT id, name, capacity, features, status FROM rooms WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $roomId]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "<p>Room not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid room ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - <?php echo htmlspecialchars($room['name']); ?></title>
    <link rel="stylesheet" href="stylish.css"> 
</head>
<body>
    <div class="container">
        <h1>Room Details</h1>
        
        <h2><?php echo htmlspecialchars($room['name']); ?></h2>
        <p><strong>Room Number:</strong> <?php echo str_pad($room['id'], 4, '0', STR_PAD_LEFT); ?></p> 
        <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
        <p><strong>Features:</strong> <?php echo htmlspecialchars($room['features']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($room['status']); ?></p>
        
        <a class="reserve-button" href="reserve_room.php?id=<?php echo $room['id']; ?>">Reserve Room</a>
        <br>
        <a class="back-button" href="browse_rooms.php">Back to Available Rooms</a>
    </div>
</body>
</html>

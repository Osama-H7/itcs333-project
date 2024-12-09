<?php
session_start();
require 'database.php';

// Get the room ID from the URL
$roomId = $_GET['id'] ?? null;

if (!$roomId) {
    echo "<p style='color: red;'>Room ID is not set. Please check the URL.</p>";
    exit; 
}

// Fetch room details based on the room ID
$sql = "SELECT id, name, capacity, features, status FROM rooms WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $roomId]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    echo "<p style='color: red;'>Room not found.</p>";
    exit; 
}

$isAvailable = ($room['status'] === 'Available');
$successMessage = ""; // Initialize success message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $reservationTime = trim($_POST['reservation_time']);
    $endTime = trim($_POST['end_time']);
    $specialRequests = trim($_POST['special_requests']);
    $userId = $_SESSION['user_id'] ?? null;

    // Validate the reservation time
    $startDateTime = new DateTime($reservationTime);
    $endDateTime = new DateTime($endTime);
    
    // Calculate the reservation duration
    $duration = $endDateTime->diff($startDateTime);
    
    // Check if the reservation duration exceeds 5 hours
    if ($duration->h > 5 || ($duration->h === 5 && $duration->i > 0)) {
        echo "<p class='alert error'>Reservation cannot exceed 5 hours.</p>";
    }
    // Check if the reservation is on a Friday or Saturday
    elseif ($startDateTime->format('N') >= 5) { // 5 = Friday, 6 = Saturday
        echo "<p class='alert error'>Reservations cannot be made on Fridays or Saturdays.</p>";
    }
    // Check for conflicting reservations
    else {
        $conflictSql = "SELECT * FROM reservations WHERE room_id = :room_id AND (
            (start_time < :end_time AND end_time > :reservation_time)
        )";
        $conflictStmt = $pdo->prepare($conflictSql);
        $conflictStmt->execute([
            'room_id' => $roomId,
            'end_time' => $endTime,
            'reservation_time' => $reservationTime
        ]);
        $conflict = $conflictStmt->fetch();

        if ($conflict) {
            echo "<p class='alert error'>This room is already booked during your selected time. Please choose a different time.</p>";
        } elseif ($userId && !empty($name) && !empty($reservationTime) && !empty($endTime)) {
            // Update room status to Not Available
            $updateStatusSql = "UPDATE rooms SET status = 'Not Available' WHERE id = :room_id";
            $updateStmt = $pdo->prepare($updateStatusSql);
            $updateStmt->execute(['room_id' => $roomId]);

            // Insert new reservation
            $insertSql = "INSERT INTO reservations (user_id, room_id, start_time, end_time, special_requests, reservation_time, name) VALUES (:user_id, :room_id, :start_time, :end_time, :special_requests, :reservation_time, :name)";
            
            try {
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->execute([
                    'user_id' => $userId,
                    'room_id' => $roomId,
                    'start_time' => $reservationTime,
                    'end_time' => $endTime,
                    'special_requests' => $specialRequests,
                    'reservation_time' => $reservationTime,
                    'name' => $name
                ]);

                // Set success message
                $successMessage = "Reservation successful for {$room['name']} from {$reservationTime} to {$endTime}.";
            } catch (PDOException $e) {
                echo "<p class='alert error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p class='alert error'>Please fill in all fields.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Room: <?php echo htmlspecialchars($room['name']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: linear-gradient(to bottom right, #f0f8ff, #e6f7ff); /* Gradient background */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="datetime-local"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="datetime-local"]:focus,
        textarea:focus {
            border-color: #007bff; /* Highlight border on focus */
        }
        button {
            background-color: #007bff; /* Button color */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
        .alert {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .room-info {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            width: 100%;
            text-align: center;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reserve Room: <?php echo htmlspecialchars($room['name']); ?></h1>

        <?php if ($successMessage): ?>
            <div class="alert success"><?php echo $successMessage; ?></div>
            <div><a class="back-button" href="view_reservations.php">View My Reservations</a></div>
        <?php else: ?>
            <form method="POST" action="">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="reservation_time">Start Time:</label>
                <input type="datetime-local" id="reservation_time" name="reservation_time" required>

                <label for="end_time">End Time:</label>
                <input type="datetime-local" id="end_time" name="end_time" required>

                <label for="special_requests">Special Requests:</label>
                <textarea id="special_requests" name="special_requests" rows="4" placeholder="Any special requirements?"></textarea>

                <button type="submit">Reserve Room</button>
            </form>

            <div class="room-info">
                <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
                <p><strong>Features:</strong> <?php echo htmlspecialchars($room['features']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($room['status']); ?></p>
            </div>
        <?php endif; ?>

        <a class="back-button" href="browse_rooms.php">Back to Room List</a>
    </div>
</body>
</html>
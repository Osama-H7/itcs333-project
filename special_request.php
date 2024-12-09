<?php
session_start();
require 'database.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: login.php");
    exit();
}

// Fetch upcoming bookings
$upcomingSql = "SELECT * FROM reservations WHERE user_id = :user_id AND start_time > NOW() ORDER BY start_time";
$upcomingStmt = $pdo->prepare($upcomingSql);
$upcomingStmt->execute(['user_id' => $userId]);
$upcomingBookings = $upcomingStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch past bookings
$pastSql = "SELECT * FROM reservations WHERE user_id = :user_id AND end_time < NOW() ORDER BY end_time DESC";
$pastStmt = $pdo->prepare($pastSql);
$pastStmt->execute(['user_id' => $userId]);
$pastBookings = $pastStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #003366;
            margin: 20px 0;
        }

        .section {
            margin: 20px auto;
            max-width: 90%;
        }

        h2 {
            color: #00509e;
            margin-top: 20px;
            padding: 10px 0;
            border-bottom: 2px solid #00509e;
            display: inline-block;
        }

        table {
            width: 100%;
            margin: 10px 0;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #003366;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-bookings {
            text-align: center;
            color: #777;
            font-style: italic;
        }

        footer {
            text-align: center;
            margin: 20px 0;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Your Bookings</h1>

    <div class="section">
        <h2>Upcoming Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Name</th>
                    <th>Special Requests</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($upcomingBookings)): ?>
                    <tr><td colspan="5" class="no-bookings">No upcoming bookings.</td></tr>
                <?php else: ?>
                    <?php foreach ($upcomingBookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['room_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['special_requests']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Past Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Name</th>
                    <th>Special Requests</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pastBookings)): ?>
                    <tr><td colspan="5" class="no-bookings">No past bookings.</td></tr>
                <?php else: ?>
                    <?php foreach ($pastBookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['room_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['special_requests']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> IT College, University of Bahrain
    </footer>
</body>
</html>

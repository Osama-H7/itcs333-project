<?php
session_start();
require 'database.php'; // Ensure you have a database connection

// Fetch reports or relevant data from the database
$sql = "SELECT * FROM reservations WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting - My Reservations</title>
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
            width: 120px; /* Adjusted for better visibility */
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 2em;
            color: #003366;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #003366;
            color: white;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003366;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #00509e;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://upload.wikimedia.org/wikipedia/en/7/71/University_of_Bahrain_logo.png" alt="University of Bahrain Logo">
    </div>
    <div class="container">
        <h1>My Reservations Report</h1>

        <?php if (count($reservations) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Room Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Special Requests</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['room_id']); // Adjust if room name is needed ?></td>
                            <td><?php echo htmlspecialchars($reservation['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['end_time']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['special_requests']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No reservations found.</p>
        <?php endif; ?>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> IT College, University of Bahrain
    </footer>
</body>
</html>
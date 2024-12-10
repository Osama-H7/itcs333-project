<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background: linear-gradient(to right, #4facfe, #00f2fe);
                margin: 0;
                color: #333;
            }
            .container {
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                text-align: center;
                width: 300px;
            }
            h1 {
                font-size: 24px;
                margin-bottom: 10px;
            }
            p {
                margin-bottom: 20px;
            }
            a {
                text-decoration: none;
                color: #4facfe;
                font-weight: bold;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Access Denied</h1>
            <p>You need to be logged in to make a reservation.</p>
            <p><a href="login.php">Log in</a> or <a href="index.php">go back</a> to the home page.</p>
        </div>
    </body>
    </html>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'];
    $date = $_POST['date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Insert the reservation into the database
    $sql = "INSERT INTO reservations (room_id, user_id, date, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$roomId, $_SESSION['user_id'], $date, $startTime, $endTime]);
        header("Location: reservation_success.php"); // Redirect to a success page
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
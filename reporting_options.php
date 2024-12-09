<?php
session_start();
require 'database.php'; // Ensure you have a database connection

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting Options</title>
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
            max-width: 600px;
            margin: 40px auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2em;
            color: #003366;
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
    </style>
</head>
<body>
    <div class="header">
        <img src="https://upload.wikimedia.org/wikipedia/en/7/71/University_of_Bahrain_logo.png" alt="University of Bahrain Logo">
    </div>
    <div class="container">
        <h1>Reporting Options</h1>
        <a href="usage_statistics.php" class="button"><i class="fas fa-chart-line"></i> Room Usage Statistics</a>
        <a href="reporting.php" class="button"><i class="fas fa-file-alt"></i> Reporting</a>
        <a href="user_activity_reports.php" class="button"><i class="fas fa-users"></i> User Activity Reports</a>
        <a href="dashboard.php" class="button">Back to Dashboard</a>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> IT College, University of Bahrain
    </footer>
</body>
</html>
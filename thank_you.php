<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #eef2f5;
        }
        h1 {
            color: #003366;
        }
        p {
            font-size: 1.2em;
            color: #555;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003366;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #00509e;
        }
    </style>
</head>
<body>
    <h1>Thank You for Your Feedback!</h1>
    <p>Your feedback has been submitted successfully.</p>
    <a href="dashboard.php">Return to Dashboard</a>
</body>
</html>
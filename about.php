<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2.5em;
            color: #007BFF; /* A professional blue color */
        }

        p {
            font-size: 1.2em;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 150px; /* Adjust the size as needed */
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1.2em;
            color: white;
            background-color: #007BFF; /* Button color */
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2em;
            }

            p {
                font-size: 1em;
            }

            .button {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>About Us</h1>
        <p>Welcome to the IT College Reservation System. We provide an efficient platform for managing room reservations.</p>
        <a href="register.php" class="button">Back to Home</a>
    </div>
</body>
</html>

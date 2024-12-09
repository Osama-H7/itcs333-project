<?php
session_start();
require 'database.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION['user_id'];
    $feedback = $_POST['feedback'];

   
    $stmt = $pdo->prepare("INSERT INTO feedback (user_id, feedback_text) VALUES (:user_id, :feedback)");
    $stmt->execute(['user_id' => $userId, 'feedback' => $feedback]);

    
    header("Location: dashboard.php?feedback_submitted=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
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
            width: 120px;
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
            margin-bottom: 10px;
            font-size: 2em;
            color: #003366;
        }

        textarea {
            width: 100%;
            height: 150px;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
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
        <h1>Submit Feedback</h1>
        <form method="POST" action="">
            <label for="feedback">Your Feedback:</label>
            <textarea id="feedback" name="feedback" required></textarea>
            <button type="submit" class="button">Submit</button>
        </form>
        <p><a href="feedback_history.php" class="button">View Feedback History</a></p>
        <p><a href="dashboard.php" class="button">Back to Dashboard</a></p>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> IT College, University of Bahrain
    </footer>
</body>
</html>
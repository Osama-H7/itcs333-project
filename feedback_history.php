<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM feedback WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $userId]);
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $feedbacks = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback History</title>
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

        .feedback {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            text-align: left;
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
        <h1>Feedback History</h1>
        <?php if (empty($feedbacks)): ?>
            <p>No feedback submitted yet.</p>
        <?php else: ?>
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback">
                    <p><?php echo htmlspecialchars($feedback['feedback_text']); ?></p>
                    <small>Submitted on: <?php echo htmlspecialchars($feedback['created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <p><a href="dashboard.php" class="button">Back to Dashboard</a></p>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> IT College, University of Bahrain
    </footer>
</body>
</html>

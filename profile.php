<?php
session_start();
require 'database.php';


if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}


$image_path = isset($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'uploads/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f4f8; /* Soft light gray */
            color: #343a40;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 3px solid #007bff; 
        }
        .profile-heading {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff; 
        }
        .profile-info {
            margin-bottom: 15px;
            font-size: 18px;
        }
        .btn-custom {
            width: 100%;
            margin: 10px 0; 
            padding: 10px;
            font-size: 16px;
        }
        .btn-primary {
            background: #007bff;   
            border: none;
        }
        .btn-secondary {
            background: #6c757d; 
            border: none;
        }
        .btn-success {
            background: #28a745; 
            border: none;
        }
        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-success:hover {
            opacity: 0.8; 
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h1 class="profile-heading">User Profile</h1>
    <img src="<?php echo $image_path; ?>" alt="Profile Picture" class="profile-picture">
    <div class="profile-info">
        <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
    </div>
    <div class="profile-info">
        <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
    </div>
    <div class="profile-info">
        <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?>
    </div>
    <div class="profile-info">
        <strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
    </div>
    <a href="edit_profile.php" class="btn btn-primary btn-custom">Edit Profile</a>
    <a href="upload_picture.php" class="btn btn-secondary btn-custom">Upload Profile Picture</a>
    <a href="special_request.php" class="btn btn-success btn-custom">My Bookings</a> <!-- New Button -->
    <a href="dashboard.php" class="btn btn-success btn-custom">Back to Dashboard</a>
</div>

</body>
</html>

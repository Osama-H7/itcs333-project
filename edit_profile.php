<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $bio = trim($_POST['bio']);


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {

        $stmt = $pdo->prepare("UPDATE users SET email = ?, username = ?, phone = ?, bio = ? WHERE id = ?");
        $stmt->execute([$email, $username, $phone, $bio, $_SESSION['user_id']]);
        $success = "Profile updated successfully.";
    }
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}


$username = isset($user['username']) ? htmlspecialchars($user['username']) : '';
$email = htmlspecialchars($user['email']);
$phone = isset($user['phone']) ? htmlspecialchars($user['phone']) : '';
$bio = isset($user['bio']) ? htmlspecialchars($user['bio']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Edit Profile</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $username; ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" value="<?php echo $phone; ?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="bio">Bio:</label>
            <textarea name="bio" class="form-control" rows="4"><?php echo $bio; ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary btn-block">Update</button>
        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='text-success'>$success</p>"; ?>
    </form>
    <a href="profile.php" class="btn btn-secondary btn-block">Back to Profile</a>
</div>

</body>
</html>

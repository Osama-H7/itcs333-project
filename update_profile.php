<?php
session_start();
require 'database.php';


if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $email = trim($_POST['email']);

   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        
        $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>
    <form action="" method="post">
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
</body>
</html>
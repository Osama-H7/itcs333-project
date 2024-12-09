<?php
session_start();
require 'database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['email'] = $user['email'];

        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #6a11cb;
        }

        input {
            width: calc(100% - 20px);
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #6a11cb;
            outline: none;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #6a11cb;
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2575fc;
        }

        .error {
            color: red;
            margin-top: 10px;
            font-size: 0.9em;
        }

        footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }

        .remember-me {
            margin: 10px 0;
            text-align: left;
        }

        a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="remember-me">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>
            <button type="submit" name="login">Log In</button>
        </form>
        <?php if (isset($login_error)): ?>
            <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> IT College Reservations
    </footer>
</body>
</html>
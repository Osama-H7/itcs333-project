<?php
session_start();
require 'database.php'; 


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $registration_error = null; 

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@(stu\.uob\.edu\.bh|uob\.edu\.bh)$/', $email)) {
        $registration_error = "Invalid email format. Please use an email ending with @stu.uob.edu.bh or @uob.edu.bh.";
    } elseif (!isStrongPassword($password)) {
        $registration_error = "Password must be at least 8 characters long, contain uppercase letters, lowercase letters, numbers, and special characters.";
    } else {
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $registration_error = "Email already registered. Please log in or use a different email.";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->execute([$email, $hashed_password]);

            
            $_SESSION['user_id'] = $pdo->lastInsertId(); 
            $_SESSION['email'] = $email; 

            
            header("Location: dashboard.php");
            exit();
        }
    }
}


function isStrongPassword($password) {
    return preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password) && 
           preg_match('/[\W_]/', $password) && 
           strlen($password) >= 8; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT College Reservation System - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-direction: column;
        overflow: hidden; /* Hide overflow */
        position: relative;
    }

    
    .background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #ff758c, #ff7eb3);
        background-size: 300% 300%;
        animation: gradient 15s ease infinite;
        z-index: -1; 
    }

    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    
    .card {
        background: rgba(255, 255, 255, 0.95); 
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 40px rgba(0, 0, 0, 0.15);
        max-width: 450px;
        width: 100%;
        transition: all 0.3s ease;
        text-align: center;
        z-index: 1; 
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 60px rgba(0, 0, 0, 0.25);
    }

    
    h1 {
        margin-bottom: 15px;
        font-size: 2.5em;
        color: #ff758c;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    
    input {
        width: 100%;
        padding: 12px;
        margin: 12px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
        transition: border-color 0.3s;
    }

    input:focus {
        border-color: #ff758c;
        outline: none;
        box-shadow: 0 0 5px rgba(255, 117, 140, 0.5);
    }

    
    button {
        width: 100%;
        padding: 12px;
        background-color: #ff758c;
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 1.1em;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
    }

    button:hover {
        background-color: #ff7eb3;
        transform: translateY(-1px);
    }

    
    .error, .success {
        color: red;
        margin-top: 10px;
        font-weight: bold;
        text-align: center;
    }

    
    .login-prompt {
        text-align: center;
        margin-top: 20px;
    }

    a {
        color: #ff758c;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
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
    <div class="background"></div> <
    <div class="card">
        <h1>Register</h1>
        <p>Create your account to get started!</p>

        <form action="" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>

        <?php if (isset($registration_error)): ?>
            <div class="error"><?php echo htmlspecialchars($registration_error); ?></div>
        <?php endif; ?>
        
        <div class="login-prompt">
            <p>Already have an account? <a href="login.php">Log In</a></p>
        </div>

        <div class="footer-links">
            <p><a href="about.php">About Us</a> | <a href="contact.php">Contact Support</a></p>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> IT College Reservations
    </footer>
</body>
</html>
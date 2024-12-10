<?php
session_start();
require 'database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'action' is set before using it
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action === 'register') {
            // Registration process
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Prepare and execute the statement
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $password]);

            // Redirect to login page after successful registration
            header('Location: login.php');
            exit;

        } elseif ($action === 'login') {
            // Login process
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Prepare and execute the statement
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Check if user exists and verify password
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['name'];
                header('Location: profile.php');
                exit;
            } else {
                echo 'Invalid credentials.';
            }
        }
    } else {
        echo "No action specified.";
        exit();
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
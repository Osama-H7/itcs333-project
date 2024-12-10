<?php
session_start();
require 'database.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action === 'register') {
            
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            i
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $password]);

        
            header('Location: login.php');
            exit;

        } elseif ($action === 'login') {
            
            $email = $_POST['email'];
            $password = $_POST['password'];


            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();


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

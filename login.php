<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];  // Сохраняем роль в сессии

        // Проверяем, если пользователь администратор, перенаправляем на админскую страницу
        if ($user['role'] === 'admin') {
            header("Location: welcome.php");
        } else {
            header("Location: welcome.php");
        }
        exit;
    } else {
        echo "Неверные данные.";
    }
}

?>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация и Вход</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script src="script.js" defer></script>
    <style>
        /* Центрирование контейнера */
        .container {
            width: 400px;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            display: none;
            flex-direction: column;
            align-items: center;
            transition: opacity 0.5s ease;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .container.active {
            display: flex;
        }
    </style>
</head>
<body>
    <!-- Меню навигации -->
   <header> 
     <div class="navbar">
        <a href="#register" id="registerLink" class="active">Регистрация</a>
        <a href="#login" id="loginLink">Вход</a>
    </div>
</header>

    <!-- Контейнер для формы регистрации -->
    <div class="container active" id="register">
        <h2>Регистрация</h2>
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>

    <!-- Контейнер для формы входа -->
    <div class="container" id="login">
        <h2>Вход</h2>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
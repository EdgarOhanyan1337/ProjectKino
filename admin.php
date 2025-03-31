<?php
session_start();

// Проверяем, есть ли активная сессия и если у пользователя роль admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Если нет, перенаправляем на главную страницу
    header("Location: welcome.php");
    exit;
}

// Подключение к базе данных
$host = 'localhost';
$dbname = 'cinema';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Проверяем и создаем папки для хранения файлов, если их нет
if (!file_exists('posters')) {
    mkdir('posters', 0777, true);  // Создаст папку для изображений
}
if (!file_exists('movies')) {
    mkdir('movies', 0777, true);  // Создаст папку для видеофайлов
}

// Обработка загрузки фильма
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['movie_file']) && isset($_POST['title']) && isset($_POST['description'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Загружаем изображение
    $image = $_FILES['image']['name'];
    $target_image = 'posters/' . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_image);

    // Загружаем видео
    $movie_file = $_FILES['movie_file']['name'];
    $target_video = 'movies/' . basename($movie_file);
    move_uploaded_file($_FILES['movie_file']['tmp_name'], $target_video);

    // Вставляем фильм в базу данных
    $stmt = $pdo->prepare("INSERT INTO movies (title, description, image, movie_file) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $target_image, $target_video]);

    echo "Фильм добавлен успешно!";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить фильм</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="welcome.php">Главная</a>
        </div>
    </header>

    <div class="admin-form">
        <h2>Добавить фильм</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Название фильма" required>
            <textarea name="description" placeholder="Описание фильма" required></textarea>
            <input type="file" name="image" accept="image/*" required>
            <input type="file" name="movie_file" accept="video/mp4,video/avi" required>
            <button type="submit">Добавить фильм</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>

<?php
session_start();
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

// Получаем список фильмов с рейтингами и постерами
$stmt = $pdo->query("SELECT * FROM movies");
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кинотеатр</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="welcome.php">Главная</a>
            <a href="#">Фильмы</a>
            <a href="#">Сериалы</a>
            <!-- <a href="profile.php">Профиль</a> -->
        </div>
    </header>

    <div class="movies-section">
        <h2>Смотрят сейчас</h2>
        <div class="movies-row">
            <?php foreach ($movies as $movie): ?>
                <div class="movie-card" onclick="location.href='movie.php?id=<?php echo $movie['id']; ?>'">
                    <!-- Здесь мы получаем путь к изображению постера -->
                    <img src="<?php echo $movie['image']; ?>" alt="<?php echo $movie['title']; ?>">
                    <span class="rating"><?php echo round($movie['average_rating'], 1); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

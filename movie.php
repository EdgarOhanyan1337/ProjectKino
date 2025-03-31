<?php
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

// Получаем данные о фильме
$movieId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$movieId]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

// Получаем текущий рейтинг фильма
$stmt = $pdo->prepare("SELECT AVG(rating) as average_rating FROM movie_ratings WHERE movie_id = ?");
$stmt->execute([$movieId]);
$ratingData = $stmt->fetch(PDO::FETCH_ASSOC);
$averageRating = round($ratingData['average_rating'], 1);

// Обработка рейтинга, если он был отправлен
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $userRating = $_POST['rating'];

    // Проверка, чтобы рейтинг был в пределах 1-10
    if ($userRating >= 1 && $userRating <= 10) {
        // Вставляем рейтинг в таблицу movie_ratings
        $stmt = $pdo->prepare("INSERT INTO movie_ratings (movie_id, rating) VALUES (?, ?)");
        $stmt->execute([$movieId, $userRating]);

        // Пересчитываем средний рейтинг для фильма
        $stmt = $pdo->prepare("SELECT AVG(rating) as average_rating FROM movie_ratings WHERE movie_id = ?");
        $stmt->execute([$movieId]);
        $ratingData = $stmt->fetch(PDO::FETCH_ASSOC);
        $newAverageRating = round($ratingData['average_rating'], 1);

        // Обновляем поле average_rating в таблице movies
        $stmt = $pdo->prepare("UPDATE movies SET average_rating = ? WHERE id = ?");
        $stmt->execute([$newAverageRating, $movieId]);

        // Обновляем переменную для отображения на странице
        $averageRating = $newAverageRating;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.3/plyr.css" />
</head>
<body>

<header>
    <div class="navbar">
        <a href="welcome.php">Главная</a>
        <a href="#">Фильмы</a>
        <a href="#">Сериалы</a>
    </div>
</header>

<div class="movie-container">
    <h2><?php echo htmlspecialchars($movie['title']); ?></h2>

    <!-- Плеер фильма -->
    <div class="movie-player">
        <video id="player" width="640" height="360" controls>
            <source src="<?php echo htmlspecialchars($movie['movie_file']); ?>" type="video/mp4">
            Ваш браузер не поддерживает видео.
        </video>
    </div>

    <!-- Система рейтинга ниже плеера -->
    <div class="rating-section">
        <h3>Оцените фильм:</h3>
        <form method="POST" action="">
            <div class="stars">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <label>
                        <input type="radio" name="rating" value="<?php echo $i; ?>" <?php echo (isset($userRating) && $userRating == $i) ? 'checked' : ''; ?>>
                        <span><?php echo $i; ?> ★</span>
                    </label>
                <?php endfor; ?>
            </div>
            <button type="submit">Оценить</button>
        </form>

        <h4>Средний рейтинг: <?php echo $averageRating; ?> / 10</h4>
    </div>
</div>

<!-- Подключение Plyr -->
<script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const player = new Plyr('#player');
        settings = ['captions', 'quality', 'speed', 'loop'];
    });
</script>

</body>
</html>

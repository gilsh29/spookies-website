<?php
include 'db.php';

// Fetch upcoming movies ordered by release date
$sql = "SELECT title, overview, release_date, poster_path FROM upcoming_movies ORDER BY release_date ASC";
$result = $conn->query($sql);

$movies = [];
while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upcoming Horror Movies - Spookies</title>
    <link rel="stylesheet" href="MainPage.css">
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        h1.page-title {
            text-align: center;
            font-family: 'Creepster', cursive;
            color: #e60000;
            margin-top: 40px;
        }

        .movie-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 40px;
        }

        .movie-card {
            width: 18%;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 8px #e60000;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .movie-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .movie-title {
            font-size: 1.1em;
            font-family: 'Creepster', cursive;
            color: #e60000;
            margin-bottom: 5px;
        }

        .movie-release {
            font-size: 0.9em;
            margin-bottom: 10px;
            color: #aaa;
        }

        .movie-overview {
            font-size: 0.85em;
            color: #ddd;
            text-align: left;
            max-height: 160px;
            overflow-y: auto;
            padding-right: 5px;
        }

        /* Scrollbar for description */
        .movie-overview::-webkit-scrollbar {
            width: 6px;
        }
        .movie-overview::-webkit-scrollbar-thumb {
            background-color: #e60000;
            border-radius: 3px;
        }

        @media (max-width: 1200px) {
            .movie-card {
                width: 28%;
            }
        }

        @media (max-width: 768px) {
            .movie-card {
                width: 45%;
            }
        }

        @media (max-width: 480px) {
            .movie-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <h1 class="page-title">🩸 Upcoming Horror Movies</h1>

    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card">
                <img src="https://image.tmdb.org/t/p/w342<?= htmlspecialchars($movie['poster_path']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
                <div class="movie-release">Release: <?= htmlspecialchars($movie['release_date']) ?></div>
                <div class="movie-overview"><?= nl2br(htmlspecialchars($movie['overview'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="logo.js"></script>

</body>
</html>

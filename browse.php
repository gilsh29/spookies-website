<?php
session_start();
require_once 'db.php';
include 'header.php';

// Fetch 50 random movies
$sql = "SELECT id, title, release_year, poster_url FROM movies ORDER BY RAND() LIMIT 50";
$result = mysqli_query($conn, $sql);

$movies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $movies[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Horror Movies</title>
    <link rel="stylesheet" href="MainPage.css">
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .browse-title {
            text-align: center;
            font-family: 'Creepster', cursive;
            font-size: 36px;
            color: #ff3333;
        }

        .movie-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
            margin-top: 30px;
        }

        .movie-card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            width: 200px;
            overflow: hidden;
            text-decoration: none;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px #ff0000;
            color: #ff3333;
        }

        .movie-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .movie-info {
            padding: 10px;
            text-align: center;
        }

        .movie-info h3 {
            font-family: 'Creepster', cursive;
            font-size: 20px;
            margin: 10px 0 5px;
            color: #ff3333;
        }

        .movie-info p {
            margin: 0;
            font-size: 14px;
            color: #ccc;
        }
    </style>
</head>
<body>

<h1 class="browse-title">Browse the Unknown 👁️‍🗨️</h1>

<div class="movie-grid">
    <?php foreach ($movies as $movie): ?>
        <a href="movie.php?id=<?= $movie['id'] ?>" class="movie-card">
            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <div class="movie-info">
                <h3><?= htmlspecialchars($movie['title']) ?></h3>
                <p><?= htmlspecialchars($movie['release_year']) ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>

<script>
// Random logo logic (if your header includes a .logo image)
const logos = [
    "images/logos/logo5.png",
    "images/logos/logo6.png",
    "images/logos/logo7.png"
];
const logo = document.querySelector(".logo");
if (logo) {
    logo.src = logos[Math.floor(Math.random() * logos.length)];
}
</script>

</body>
</html>

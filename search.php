<?php
session_start();
require_once 'db.php';
include 'header.php';

$input = isset($_GET['q']) ? trim($_GET['q']) : '';
$year = isset($_GET['year']) ? trim($_GET['year']) : '';

$query = $input;

// Extract 4-digit year from the query if exists
if (preg_match('/\b(19|20)\d{2}\b/', $input, $matches)) {
    $year = $matches[0];
    // Remove the year from the query string
    $query = trim(str_replace($year, '', $input));
}

$results = [];

if (!empty($query)) {
    $like = '%' . strtolower($query) . '%';
    if (!empty($year)) {
        $sql = "SELECT id, title, release_year, poster_url FROM movies 
                WHERE LOWER(title) LIKE ? AND release_year = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $like, $year);
    } else {
        $sql = "SELECT id, title, release_year, poster_url FROM movies 
                WHERE LOWER(title) LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $like);
    }

    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="MainPage.css">
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .search-results-title {
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

<h1 class="search-results-title">Search Results for "<?= htmlspecialchars($query) ?>"</h1>

<?php if (count($results) === 0): ?>
    <p style="text-align: center; margin-top: 40px;">No movies found.</p>
<?php else: ?>
    <div class="movie-grid">
        <?php foreach ($results as $movie): ?>
            <a href="movie.php?id=<?= $movie['id'] ?>" class="movie-card">
                <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <div class="movie-info">
                    <h3><?= htmlspecialchars($movie['title']) ?></h3>
                    <p><?= htmlspecialchars($movie['release_year']) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>

<script>
// Random logo logic 
    const logos = [
        "images/logos/logo5.png",
        "images/logos/logo6.png",
        "images/logos/logo7.png"
    ];
    document.querySelector(".logo").src = logos[Math.floor(Math.random() * logos.length)];

</script>
</body>
</html>

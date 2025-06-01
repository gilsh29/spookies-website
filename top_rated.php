<?php
// top_rated.php
include 'db.php';
include 'header.php';

// Sorting logic
$sort_by = $_GET['sort'] ?? 'avg_rating';
$order_by = match ($sort_by) {
    'num_ratings' => 'num_ratings DESC, avg_rating DESC',
    default => 'avg_rating DESC, num_ratings DESC',
};

// Pagination logic
$page = max(1, intval($_GET['page'] ?? 1));
$movies_per_page = 10;
$offset = ($page - 1) * $movies_per_page;

// Count total movies
$count_sql = "SELECT COUNT(DISTINCT m.id) AS total
              FROM movies m JOIN user_ratings ur ON m.id = ur.movie_id";
$total_result = $conn->query($count_sql);
$total_movies = ($total_result && $total_result->num_rows > 0) 
                ? intval($total_result->fetch_assoc()['total']) 
                : 0;
$total_pages = ceil($total_movies / $movies_per_page);

// Fetch movies
$sql = "
    SELECT 
        m.id, m.title, m.poster_url, 
        ROUND(AVG(ur.rating), 2) AS avg_rating, 
        COUNT(ur.rating) AS num_ratings
    FROM movies m
    JOIN user_ratings ur ON m.id = ur.movie_id
    GROUP BY m.id
    HAVING num_ratings >= 1
    ORDER BY $order_by
    LIMIT $movies_per_page OFFSET $offset
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Top Rated Horror Movies - Spookies</title>
    <link rel="stylesheet" href="MainPage.css">
    <style>
    /* Horror-themed heading (subtle) */
        h2 {
            font-family: 'Creepster', cursive, 'Chiller', cursive, serif;
            font-size: 2.5rem;
            color: #cc0000; /* Dark blood red */
            text-shadow:
                1px 1px 2px #660000,
                0 0 5px #990000;
            margin-bottom: 25px;
        }

        /* Sort bar container */
        .sort-container {
            margin-bottom: 20px;
            font-family: 'Creepster', cursive, 'Chiller', cursive, serif;
            color: #cc3333;
        }

        /* Label styling */
        .sort-container label {
            font-size: 1.1rem;
            margin-right: 10px;
            text-shadow: 1px 1px 2px #660000;
        }

        /* Select dropdown */
        .sort-container select {
            font-family: 'inherit';
            font-size: 1.05rem;
            background-color: #330000;
            color: #ff4444;
            border: 1.5px solid #990000;
            border-radius: 5px;
            padding: 6px 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sort-container select:hover,
        .sort-container select:focus {
            background-color: #440000;
            color: #ff6666;
            outline: none;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }

        .movie-card {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            background-color: rgba(20, 0, 0, 0.85);
            border: 1px solid #e60000;
            border-radius: 8px;
            padding: 10px;
            color: #f5f5f5;
            transition: transform 0.3s;
        }

        .movie-card:hover {
            transform: scale(1.02);
        }

        .movie-card img {
            width: 100px;
            border-radius: 4px;
        }

        .movie-info h3 {
            margin: 0;
            font-size: 1.2em;
        }

        .movie-info p {
            margin: 4px 0;
        }

        .movie-info a {
            color: #ff3333;
            text-decoration: none;
        }

        .movie-info a:hover {
            text-decoration: underline;
        }

        .sort-container {
            margin-bottom: 20px;
        }

        .pagination {
            text-align: center;
            margin-top: 30px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #111;
            color: #eee;
            text-decoration: none;
            border: 1px solid #444;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #e60000;
            color: white;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #ff3333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🎖️ Top Rated Horror Movies</h2>

    <div class="sort-container">
        <form method="GET" action="top_rated.php">
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="avg_rating" <?= $sort_by === 'avg_rating' ? 'selected' : '' ?>>Average Rating</option>
                <option value="num_ratings" <?= $sort_by === 'num_ratings' ? 'selected' : '' ?>>Number of Ratings</option>
            </select>
        </form>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="movie-card">
                <img src="<?= htmlspecialchars($row['poster_url']) ?>" alt="<?= htmlspecialchars($row['title']) ?> Poster">
                <div class="movie-info">
                    <h3><a href="movie.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>
                    <p>⭐ <?= $row['avg_rating'] ?> / 10</p>
                    <p>(<?= $row['num_ratings'] ?> ratings)</p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No rated movies yet. Be the first to rate!</p>
    <?php endif; ?>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?sort=<?= htmlspecialchars($sort_by) ?>&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="logo.js"></script>
</body>
</html>

<?php $conn->close(); ?>

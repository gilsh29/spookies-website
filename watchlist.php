<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_name'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['user_name'];

// Handle remove request (if any)
if (isset($_POST['remove_movie_id']) && is_numeric($_POST['remove_movie_id'])) {
    $remove_id = intval($_POST['remove_movie_id']);
    $stmt_remove = $conn->prepare("DELETE FROM user_watchlist WHERE username = ? AND movie_id = ?");
    $stmt_remove->bind_param("si", $username, $remove_id);
    $stmt_remove->execute();
    // Optional: redirect to avoid resubmission
    header("Location: watchlist.php");
    exit();
}

// Fetch movies in user watchlist with description
$sql = "
    SELECT m.id, m.title, m.poster_url, m.description, m.release_year 
    FROM user_watchlist uw
    JOIN movies m ON uw.movie_id = m.id
    WHERE uw.username = ?
    ORDER BY uw.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($username) ?>'s Watchlist - Spookies</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="MainPage.css" />
    <style>
        .watchlist-container {
            max-width: 900px;
            margin: 60px auto;
            background-color: rgba(0,0,0,0.85);
            padding: 30px;
            border-radius: 10px;
            color: #fff;
            font-family: Arial, sans-serif;
            box-shadow: 0 0 10px #e60000;
        }
        .watchlist-title {
            font-family: 'Creepster', cursive;
            font-size: 2.5em;
            color: #e60000;
            text-align: center;
            margin-bottom: 20px;
        }
        .movie-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 1px solid #e60000;
            padding-bottom: 15px;
        }
        .movie-poster {
            width: 100px;
            height: auto;
            border-radius: 6px;
            margin-right: 20px;
            box-shadow: 0 0 6px #e60000;
            flex-shrink: 0;
        }
        .movie-info {
            flex: 1;
        }
        .movie-info h3 {
            margin: 0 0 6px 0;
            color: #ff4d4d;
        }
        .movie-info p.description {
            margin: 6px 0 10px 0;
            font-size: 0.9em;
            line-height: 1.3;
        }
        .movie-info p.year {
            margin: 0 0 10px 0;
            font-weight: bold;
            color: #ff6666;
        }
        .movie-link {
            color: #e60000;
            text-decoration: none;
            font-weight: bold;
        }
        .movie-link:hover {
            text-decoration: underline;
        }
        .remove-button {
            background-color: #cc0000;
            border: none;
            color: white;
            padding: 8px 14px;
            font-size: 0.9em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            align-self: center;
            margin-left: 20px;
            flex-shrink: 0;
        }
        .remove-button:hover {
            background-color: #ff3300;
        }
        .empty-msg {
            text-align: center;
            font-style: italic;
            margin-top: 40px;
            font-size: 1.2em;
            color: #ff9999;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="watchlist-container">
        <h1 class="watchlist-title"><?= htmlspecialchars($username) ?>'s Watchlist</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($movie = $result->fetch_assoc()): ?>
                <div class="movie-item">
                    <?php if (!empty($movie['poster_url'])): ?>
                        <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> poster" class="movie-poster" />
                    <?php endif; ?>
                    <div class="movie-info">
                        <h3><a href="movie.php?id=<?= $movie['id'] ?>" class="movie-link"><?= htmlspecialchars($movie['title']) ?></a></h3>
                        <p class="description"><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
                        <p class="year">Year: <?= htmlspecialchars($movie['release_year']) ?></p>
                    </div>
                    <form method="POST" onsubmit="return confirm('Remove this movie from your watchlist?');">
                        <input type="hidden" name="remove_movie_id" value="<?= $movie['id'] ?>">
                        <button type="submit" class="remove-button">Remove</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty-msg">Your watchlist is empty. Start adding some spooky movies!</p>
        <?php endif; ?>

    </main>
    <script src="logo.js"></script>

    <?php include 'footer.php'; ?>
</body>
</html>

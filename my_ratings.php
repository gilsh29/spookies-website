<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_name'])) {
    echo "❌ You must be logged in to view your ratings.";
    exit();
}

$username = $_SESSION['user_name'];

// Handle remove rating
if (isset($_POST['remove_rating_id']) && is_numeric($_POST['remove_rating_id'])) {
    $remove_id = intval($_POST['remove_rating_id']);
    $stmt_remove = $conn->prepare("DELETE FROM user_ratings WHERE username = ? AND movie_id = ?");
    $stmt_remove->bind_param("si", $username, $remove_id);
    $stmt_remove->execute();
    header("Location: my_ratings.php");
    exit();
}

// Handle update rating
if (isset($_POST['edit_rating_id'], $_POST['new_rating']) && 
    is_numeric($_POST['edit_rating_id']) && 
    is_numeric($_POST['new_rating'])) {

    $edit_id = intval($_POST['edit_rating_id']);
    $new_rating = intval($_POST['new_rating']);

    // Clamp rating between 1 and 10
    if ($new_rating < 1) $new_rating = 1;
    if ($new_rating > 10) $new_rating = 10;

    $stmt_update = $conn->prepare("UPDATE user_ratings SET rating = ?, rated_at = NOW() WHERE username = ? AND movie_id = ?");
    $stmt_update->bind_param("isi", $new_rating, $username, $edit_id);
    $stmt_update->execute();
    header("Location: my_ratings.php");
    exit();
}

// Fetch user-rated movies and their details (same as before)
$sort_option = $_GET['sort'] ?? 'recent';

switch ($sort_option) {
    case 'highest':
        $order_by = "ur.rating DESC";
        break;
    case 'lowest':
        $order_by = "ur.rating ASC";
        break;
    case 'alpha':
        $order_by = "m.title ASC";
        break;
    case 'recent':
    default:
        $order_by = "ur.rated_at DESC";
        break;
}

$sql = "
    SELECT m.id, m.title, m.poster_url, ur.rating
    FROM user_ratings ur
    JOIN movies m ON ur.movie_id = m.id
    WHERE ur.username = ?
    ORDER BY $order_by
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$ratings = [];
while ($row = $result->fetch_assoc()) {
    $ratings[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Ratings - Spookies</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="MainPage.css" />
    <style>
        .ratings-container {
            max-width: 1000px;
            margin: 60px auto;
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px;
            border-radius: 10px;
            color: #fff;
            font-family: Arial, sans-serif;
            box-shadow: 0 0 10px #e60000;
        }
        .ratings-title {
            font-family: 'Creepster', cursive;
            font-size: 2.5em;
            color: #e60000;
            text-align: center;
            margin-bottom: 30px;
        }
        .movie-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
        }
        .movie-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
            border-radius: 5px;
        }
        .movie-info {
            flex: 1;
        }
        .movie-info h3 {
            margin: 0;
            color: #fff;
        }
        .movie-info p {
            margin: 5px 0 0;
            font-size: 1.1em;
            color: #e60000;
        }
        select {
            background-color: #111;
            color: #e60000;
            border: 1px solid #e60000;
            padding: 5px 10px;
            border-radius: 4px;
            }
            label {
                margin-right: 5px;
                color: #fff;
            }

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="ratings-container">
        <h1 class="ratings-title">🎃 My Spooky Ratings</h1>
        <form method="get" style="text-align: right; margin-bottom: 20px;">
        <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="recent" <?= $sort_option === 'recent' ? 'selected' : '' ?>>Most Recent</option>
                <option value="highest" <?= $sort_option === 'highest' ? 'selected' : '' ?>>Highest Rating</option>
                <option value="lowest" <?= $sort_option === 'lowest' ? 'selected' : '' ?>>Lowest Rating</option>
                <option value="alpha" <?= $sort_option === 'alpha' ? 'selected' : '' ?>>A–Z Title</option>
            </select>
        </form>

        <?php if (count($ratings) > 0): ?>
    <?php foreach ($ratings as $entry): ?>
        <div class="movie-item" data-movie-id="<?= $entry['id'] ?>">
            <a href="movie.php?id=<?= $entry['id'] ?>">
                <img src="<?= htmlspecialchars($entry['poster_url']) ?>" alt="<?= htmlspecialchars($entry['title']) ?>">
            </a>
            <div class="movie-info">
                <h3><a href="movie.php?id=<?= $entry['id'] ?>" style="color: #fff; text-decoration: none;">
                    <?= htmlspecialchars($entry['title']) ?>
                </a></h3>
                <p>👻 Your Rating: <strong class="rating-value"><?= $entry['rating'] ?>/10</strong></p>

                <!-- Edit rating form (hidden by default) -->
                <form method="POST" class="edit-rating-form" style="display:none; margin-top:8px;">
                    <input type="hidden" name="edit_rating_id" value="<?= $entry['id'] ?>">
                    <label for="new_rating_<?= $entry['id'] ?>" style="color:#fff;">New Rating:</label>
                    <input type="number" id="new_rating_<?= $entry['id'] ?>" name="new_rating" min="1" max="10" value="<?= $entry['rating'] ?>" required style="width: 50px;">
                    <button type="submit" style="background-color:#e60000; color:#fff; border:none; border-radius:4px; padding:4px 10px; cursor:pointer; margin-left:8px;">Save</button>
                    <button type="button" class="cancel-edit" style="background-color:#555; color:#fff; border:none; border-radius:4px; padding:4px 10px; cursor:pointer; margin-left:8px;                      ">Cancel</button>
                </form>

                <!-- Action buttons -->
                <button class="edit-button" style="margin-top:8px; background-color:#cc0000; border:none; color:#fff; border-radius:5px; padding:6px 12px; cursor:pointer;">Edit Rating</button>

                <form method="POST" onsubmit="return confirm('Remove this rating?');" style="display:inline;">
                    <input type="hidden" name="remove_rating_id" value="<?= $entry['id'] ?>">
                    <button type="submit" style="background-color:#660000; border:none; color:#fff; border-radius:5px; padding:6px 12px; cursor:pointer; margin-left:12px;">Remove</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>You haven't rated any movies yet. Start haunting! 👻</p>
<?php endif; ?>

    </main>

    <?php include 'footer.php'; ?>

    <script src="logo.js"></script>
    <script>
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', () => {
            const movieItem = button.closest('.movie-item');
            const form = movieItem.querySelector('.edit-rating-form');
            button.style.display = 'none';
            form.style.display = 'block';
        });
    });

    document.querySelectorAll('.cancel-edit').forEach(button => {
        button.addEventListener('click', () => {
            const form = button.closest('.edit-rating-form');
            const movieItem = form.closest('.movie-item');
            form.style.display = 'none';
            movieItem.querySelector('.edit-button').style.display = 'inline-block';
        });
    });
    </script>

</body>
</html>

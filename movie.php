<?php
session_start();
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Invalid movie ID.";
    exit();
}

$movie_id = intval($_GET['id']);

// Fetch movie from DB
$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $movie_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($movie = mysqli_fetch_assoc($result)) {
    // Movie found
} else {
    echo "❌ Movie not found.";
    exit();
}

// Fetch providers for this movie
$provider_stmt = $conn->prepare("SELECT provider_name, provider_logo, provider_link FROM movie_providers WHERE movie_id = ?");
$provider_stmt->bind_param("i", $movie_id);
$provider_stmt->execute();
$providers_result = $provider_stmt->get_result();

$providers = [];
while ($provider = $providers_result->fetch_assoc()) {
    $providers[] = $provider;
}

$current_rating = null;
if (isset($_SESSION['user_name'])) {
    $username = $_SESSION['user_name'];
    $check_rating = $conn->prepare("SELECT rating FROM user_ratings WHERE username = ? AND movie_id = ?");
    $check_rating->bind_param("si", $username, $movie_id);
    $check_rating->execute();
    $rating_result = $check_rating->get_result();
    if ($rating_row = $rating_result->fetch_assoc()) {
        $current_rating = $rating_row['rating'];
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($movie['title']) ?> - Spookies</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="MainPage.css" />
    <style>
        .movie-container {
            max-width: 800px;
            margin: 60px auto;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
            color: #fff;
            font-family: Arial, sans-serif;
            box-shadow: 0 0 10px #e60000;
        }
        .movie-title {
            font-family: 'Creepster', cursive;
            font-size: 2.5em;
            color: #e60000;
            text-align: center;
        }
        .poster {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
            display: block;
        }
        iframe {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            border: none;
        }
        .providers img:hover {
        transform: scale(1.1);
        box-shadow: 0 0 8px #e60000;
        transition: 0.2s ease-in-out;
        }

  .spooky-slider {
    -webkit-appearance: none;
    width: 100%;
    height: 15px;
    background: linear-gradient(to right, black, red);
    border-radius: 10px;
    outline: none;
    margin-top: 10px;
    margin-bottom: 10px;
    transition: background 0.3s ease;
}

    .spooky-slider::-webkit-slider-thumb,
    .spooky-slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #ff0000;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 0 10px red;
    }

    #spook-level {
        font-weight: bold;
        color: #e60000;
        font-size: 1.2em;
    }

    #ghost {
        font-size: 1.3em;
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
    }
    .poster-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .poster {
        max-width: 450px;
        width: 100%;
        height: auto;
        border: 2px solid #e60000;
        border-radius: 8px;
    }


    </style>
</head>
<body>
    <?php include 'header.php'; ?>


   <main class="movie-container">
    <h1 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h1>

    <?php if (!empty($movie['poster_url'])): ?>
       <div class="poster-wrapper">
    <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="Poster of <?= htmlspecialchars($movie['title']) ?>" class="poster" />
    </div>

    <?php endif; ?>

    <p><strong>Release Year:</strong> <?= htmlspecialchars($movie['release_year']) ?></p>
    <p><strong>Synopsis:</strong><br><?= nl2br(htmlspecialchars($movie['description'])) ?></p>

    <?php if (!empty($movie['trailer_url'])): ?>
        <h3 style="margin-top: 30px;">🎬 Watch Trailer</h3>
        <iframe src="<?= htmlspecialchars($movie['trailer_url']) ?>" allowfullscreen></iframe>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_name'])): ?>
    <h3>🎃 Spooky Meter (Rate this movie)</h3>
    <form method="post" action="rate_movie.php">
        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
        <label for="rating">Rate the Spookiness: <span id="ghost">👻</span></label>
        <?php $initial_rating = $current_rating ?? 5; ?>
        <input type="range" name="rating" id="rating" min="1" max="10" value="<?= $initial_rating ?>" class="spooky-slider" oninput="updateSpookLevel(this.value)">
        <span id="spook-level"><?= $initial_rating ?></span>/10




        <button type="submit">Submit Rating</button>

        <?php if ($current_rating): ?>
        <p><em>You previously rated this movie: <?= $current_rating ?>/10 👻</em></p>
        <?php endif; ?>

    </form>
    <?php else: ?>
        <p><em>Sign in to rate this movie 🎃</em></p>
    <?php endif; ?>

    
    <?php if (count($providers) > 0): ?>
        <h3 style="margin-top: 30px;">📺 Available On</h3>
        <div class="providers" style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px;">
            <?php foreach ($providers as $p): ?>
                <a href="<?= htmlspecialchars($p['provider_link']) ?>" target="_blank" title="<?= htmlspecialchars($p['provider_name']) ?>">
                    <img src="<?= htmlspecialchars($p['provider_logo']) ?>" alt="<?= htmlspecialchars($p['provider_name']) ?>" style="height: 40px; border-radius: 5px;" />
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
   
    <?php if (isset($_SESSION['user_name'])): ?>
    <form method="post" action="watchlist_add.php" style="margin-top: 15px;">
        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
        <button type="submit">Add to Watchlist</button>
    </form>
    <?php endif; ?>
</main>


    <?php include 'footer.php'; ?>


    <script>
    function updateSpookLevel(val) {
        const spookMessages = [
            "Not spooky at all...",
            "Mildly eerie...",
            "Spooky shadows ahead...",
            "Creeping chills...",
            "Bone-chilling!",
            "Nightmare fuel!",
            "Doom incoming...",
            "Full terror mode!",
            "Run! RUN!",
            "Eldritch horror!!!"
        ];

        // ✅ Use of string
        document.getElementById('spook-level').textContent = val;

        // ✅ Use of loop + conditional
        for (let i = 0; i < spookMessages.length; i++) {
            if (parseInt(val) === i + 1) {
                console.log("Spookiness level:", spookMessages[i]); // Optional debug
            }
        }
    }
</script>


    <script src="logo.js"></script>

</body>
</html>

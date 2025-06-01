<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: signin.php");
    exit();
}

$username = $_SESSION['user_name'];
$movie_id = intval($_POST['movie_id']);
$rating = intval($_POST['rating']);

if ($rating < 1 || $rating > 10) {
    echo "❌ Invalid rating.";
    exit();
}

// Check if the user already rated this movie
$check = $conn->prepare("SELECT * FROM user_ratings WHERE username = ? AND movie_id = ?");
$check->bind_param("si", $username, $movie_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // Update existing rating
    $stmt = $conn->prepare("UPDATE user_ratings SET rating = ? WHERE username = ? AND movie_id = ?");
    $stmt->bind_param("isi", $rating, $username, $movie_id);
    $stmt->execute();
} else {
    // Insert new rating
    $stmt = $conn->prepare("INSERT INTO user_ratings (username, movie_id, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $username, $movie_id, $rating);
    $stmt->execute();
}

header("Location: movie.php?id=$movie_id");
exit();
?>

<?php
session_start();
include 'db.php';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added_to_watchlist') {
        echo "<p style='color: #0f0;'>Movie added to your watchlist!</p>";
    } elseif ($_GET['msg'] === 'already_in_watchlist') {
        echo "<p style='color: #ff0;'>This movie is already in your watchlist.</p>";
    }
}


if (!isset($_SESSION['user_name'])) {
    // Not logged in, redirect to login page or deny access
    header('Location: signin.php');
    exit();
}

if (!isset($_POST['movie_id']) || !is_numeric($_POST['movie_id'])) {
    echo "❌ Invalid movie ID.";
    exit();
}

$username = $_SESSION['user_name'];
$movie_id = intval($_POST['movie_id']);

// Check if already in watchlist
$sql_check = "SELECT id FROM user_watchlist WHERE username = ? AND movie_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("si", $username, $movie_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Already in watchlist, redirect back with a message (optional)
    header("Location: movie.php?id=$movie_id&msg=already_in_watchlist");
    exit();
}

// Insert into watchlist
$sql_insert = "INSERT INTO user_watchlist (username, movie_id) VALUES (?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("si", $username, $movie_id);
if ($stmt_insert->execute()) {
    header("Location: movie.php?id=$movie_id&msg=added_to_watchlist");
} else {
    echo "❌ Failed to add to watchlist.";
}

exit();
?>

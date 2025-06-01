<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

function fetchTmdbPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        return false;
    }

    curl_close($ch);
    return $result;
}

$apiKey = '6414557859bc6e6b1e92df1a5488d647';
$today = date('Y-m-d');
$page = 1;
$totalPages = 1;

do {
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&language=en-US&sort_by=release_date.asc&include_adult=false&include_video=false&page=$page&with_genres=27&primary_release_date.gte=$today";
    $response = fetchTmdbPage($url);

    if ($response === false) {
        die("❌ Could not fetch data from TMDb.");
    }

    $data = json_decode($response, true);

    if (!isset($data['results'])) {
        echo "<pre>";
        print_r($response);
        echo "</pre>";
        die("❌ Invalid API response.");
    }

    if ($page === 1 && isset($data['total_pages'])) {
        $totalPages = min($data['total_pages'], 10); // You can increase this up to TMDb's limits
    }

    foreach ($data['results'] as $movie) {
        $release_date = $movie['release_date'] ?? null;
        if (empty($release_date) || $release_date < $today) {
            continue;
        }

        $tmdb_id = $movie['id'];
        $title = $conn->real_escape_string($movie['title']);
        $overview = $conn->real_escape_string($movie['overview']);
        $poster_path = $conn->real_escape_string($movie['poster_path'] ?? '');

        if (empty($poster_path)) {
            continue;
        }

        $check = $conn->prepare("SELECT id FROM upcoming_movies WHERE tmdb_id = ?");
        $check->bind_param("i", $tmdb_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $insert = $conn->prepare("INSERT INTO upcoming_movies (title, overview, release_date, poster_path, tmdb_id) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("ssssi", $title, $overview, $release_date, $poster_path, $tmdb_id);
            if ($insert->execute()) {
                echo "✅ Inserted: $title ($release_date)<br>";
            } else {
                echo "❌ Insert error for: $title<br>";
            }
        } else {
            echo "🔁 Skipped (already exists): $title<br>";
        }

        $check->close();
    }

    $page++;
} while ($page <= $totalPages);

$conn->close();
?>

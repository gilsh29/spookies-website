<?php
include 'db.php';

$apiKey = '6414557859bc6e6b1e92df1a5488d647';
$genreId = 27; // Horror
$totalPages = 50; // Adjust as needed (TMDB allows up to 500 pages max per request type)

for ($page = 1; $page <= $totalPages; $page++) {
    echo "<h3>🔄 Fetching page $page...</h3>";
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&with_genres=27&language=en-US&page=$page&primary_release_date.gte=2020-01-01&primary_release_date.lte=2029-12-31";


    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!isset($data['results'])) {
        echo "❌ Failed to fetch page $page<br>";
        continue;
    }

    foreach ($data['results'] as $movie) {
        $tmdb_id = $movie['id'];
        $title = $movie['title'] ?? '';
        $release_year = substr($movie['release_date'] ?? '', 0, 4);
        $description = $movie['overview'] ?? '';
        $poster_path = $movie['poster_path'];
        if (!$poster_path) continue; // Skip if no poster
        $poster_url = "https://image.tmdb.org/t/p/w500" . $poster_path;

        // Check if movie already exists
        $checkStmt = $conn->prepare("SELECT id FROM movies WHERE tmdb_id = ?");
        $checkStmt->bind_param("i", $tmdb_id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "⚠️ Skipped (duplicate): $title<br>";
            continue;
        }

        // Fetch trailer
        $trailer_url = "";
        $trailer_response = file_get_contents("https://api.themoviedb.org/3/movie/$tmdb_id/videos?api_key=$apiKey&language=en-US");
        $trailer_data = json_decode($trailer_response, true);
        if (isset($trailer_data['results'])) {
            foreach ($trailer_data['results'] as $video) {
                if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                    $trailer_url = "https://www.youtube.com/embed/" . $video['key'];
                    break;
                }
            }
        }

        // Insert movie
        $insertStmt = $conn->prepare("INSERT INTO movies (tmdb_id, title, genre, release_year, description, poster_url, trailer_url) VALUES (?, ?, 'Horror', ?, ?, ?, ?)");
        $insertStmt->bind_param("isisss", $tmdb_id, $title, $release_year, $description, $poster_url, $trailer_url);
        $insertStmt->execute();
        echo "✅ Added: $title<br>";
    }

    // Optional: delay to avoid rate limit
    usleep(300000); // 0.3 seconds between requests
}
?>

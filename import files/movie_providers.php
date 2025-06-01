<?php
include 'db.php';

$apiKey = '6414557859bc6e6b1e92df1a5488d647';

$result = $conn->query("SELECT id, tmdb_id, title FROM movies");

while ($row = $result->fetch_assoc()) {
    $movieId = $row['id'];
    $tmdbId = $row['tmdb_id'];
    $title = $row['title'];

    // Fetch provider info from TMDB
    $url = "https://api.themoviedb.org/3/movie/$tmdbId/watch/providers?api_key=$apiKey";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!isset($data['results']['US']['flatrate'])) {
        echo "❌ No providers for TMDB ID $tmdbId<br>";
        continue;
    }

    foreach ($data['results']['US']['flatrate'] as $provider) {
        $name = $provider['provider_name'];
        $logo = "https://image.tmdb.org/t/p/original" . $provider['logo_path'];

        // Generate direct search link for each provider
        switch (strtolower($name)) {
            case 'netflix':
                $link = "https://www.netflix.com/search?q=" . urlencode($title);
                break;
            case 'disney+':
            case 'disney plus':
                $link = "https://www.disneyplus.com/search/" . urlencode($title);
                break;
            case 'hulu':
                $link = "https://www.hulu.com/search?q=" . urlencode($title);
                break;
            case 'amazon prime video':
            case 'prime video':
                $link = "https://www.amazon.com/s?k=" . urlencode($title) . "+movie";
                break;
            case 'apple tv':
            case 'apple tv+':
                $link = "https://tv.apple.com/search/" . urlencode($title);
                break;
            default:
                $link = "https://www.themoviedb.org/movie/$tmdbId/watch";
        }

        // Check for duplicate before insert
        $check = $conn->prepare("SELECT id FROM movie_providers WHERE movie_id = ? AND provider_name = ?");
        $check->bind_param("is", $movieId, $name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "⚠️ Skipped (duplicate): $name for Movie ID $movieId<br>";
            continue;
        }

        // Insert provider
        $stmt = $conn->prepare("INSERT INTO movie_providers (movie_id, provider_name, provider_logo, provider_link) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $movieId, $name, $logo, $link);
        $stmt->execute();

        echo "✅ Added provider: $name for Movie ID $movieId<br>";
    }

    usleep(300000); // Prevent rate-limiting
}
?>
